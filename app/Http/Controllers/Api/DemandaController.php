<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Demanda;
use App\Models\DemandasFotosKm;
use App\Models\DemandaGpsTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DemandaController extends Controller
{
    public function index()
    {
        $demandas = Demanda::where('status', 'Pendente')
                           ->with('gpsTracks')
                           ->latest()
                           ->get();

        return response()->json($demandas);
    }

    public function aceitar(Request $request, Demanda $demanda)
    {
        if ($demanda->status !== 'Pendente') {
            return response()->json(['message' => 'Esta demanda não está mais disponível.'], 409);
        }

        $demanda->motoboy_id = Auth::id();
        $demanda->status = 'Aceita';
        $demanda->data_aceite = now();
        $demanda->save();

        return response()->json([
            'message' => 'Demanda aceita com sucesso!',
            'demanda' => $demanda->load('gpsTracks')
        ]);
    }

    public function iniciar(Request $request, Demanda $demanda)
    {
        $request->validate([
            'foto_km_inicio' => 'required|image|max:5120',
            'km_inicial' => 'required|integer|min:0',
        ]);

        if ($demanda->motoboy_id !== Auth::id() || $demanda->status !== 'Aceita') {
            return response()->json(['message' => 'Ação não autorizada.'], 403);
        }

        $path = $request->file('foto_km_inicio')->store('km_fotos', 'public');
        $url  = Storage::disk('public')->url($path);

        DemandasFotosKm::create([
            'demanda_id' => $demanda->id,
            'foto_url_inicio' => $url,
        ]);

        $demanda->status = 'Em Rota';
        $demanda->km_inicial = $request->km_inicial;
        $demanda->save();

        return response()->json(['message' => 'Demanda iniciada com sucesso!']);
    }

    public function finalizar(Request $request, Demanda $demanda)
    {
        $request->validate([
            'foto_km_final' => 'required|image|max:5120',
            'km_final' => 'required|integer|min:0',
        ]);

        if ($demanda->motoboy_id !== Auth::id() || $demanda->status !== 'Em Rota') {
            return response()->json(['message' => 'Ação não autorizada.'], 403);
        }

        if ($request->km_final < $demanda->km_inicial) {
            return response()->json(['message' => 'O KM final não pode ser menor que o KM inicial.'], 422);
        }

        $path = $request->file('foto_km_final')->store('km_fotos', 'public');
        $url  = Storage::disk('public')->url($path);

        $registroFotos = DemandasFotosKm::where('demanda_id', $demanda->id)->first();
        if ($registroFotos) {
            $registroFotos->foto_url_final = $url;
            $registroFotos->save();
        }

        $demanda->status = 'Finalizada';
        $demanda->data_finalizacao = now();
        $demanda->km_final = $request->km_final;
        $demanda->save();

        return response()->json(['message' => 'Demanda finalizada com sucesso!']);
    }

    public function minhasDemandas()
    {
        $demandas = Demanda::where('motoboy_id', Auth::id())
                           ->whereIn('status', ['Aceita', 'Em Rota'])
                           ->with('gpsTracks')
                           ->latest()
                           ->get();

        return response()->json($demandas);
    }

    public function iniciarUrgente(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'veiculo_id'     => 'required|exists:veiculos,id',
            'km_inicial'     => 'required|integer|min:0',
            'foto_km_inicio' => 'required|image|max:5120',
            'descricao'      => 'nullable|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $demanda = Demanda::create([
                'titulo'        => 'Demanda Urgente - ' . now()->format('d/m/Y H:i'),
                'descricao'     => $request->descricao,
                'tipo'          => 'urgente',
                'status'        => 'Em Rota',
                'secretaria_id' => null,
                'motoboy_id'    => Auth::id(),
                'veiculo_id'    => $request->veiculo_id,
                'km_inicial'    => $request->km_inicial,
                'data_aceite'   => now(),
            ]);

            $path = $request->file('foto_km_inicio')->store('km_fotos', 'public');
            $url  = Storage::disk('public')->url($path);

            DemandasFotosKm::create([
                'demanda_id'      => $demanda->id,
                'foto_url_inicio' => $url,
            ]);

            return response()->json([
                'message' => 'Demanda urgente iniciada!',
                'demanda' => $demanda->load('gpsTracks'),
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Falha ao iniciar demanda urgente.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function finalizarUrgente(Request $request, Demanda $demanda)
    {
        return $this->finalizar($request, $demanda);
    }

    public function storeTrack(Request $request, Demanda $demanda)
    {
        $request->validate(['track' => 'required|array']);

        if ($demanda->motoboy_id !== Auth::id()) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        foreach ($request->track as $point) {
            if (!isset($point['latitude'], $point['longitude'], $point['timestamp'])) {
                continue;
            }

            DemandaGpsTrack::create([
                'demanda_id' => $demanda->id,
                'latitude'   => $point['latitude'],
                'longitude'  => $point['longitude'],
                'recorded_at'=> Carbon::parse($point['timestamp']),
            ]);
        }

        return response()->json(['message' => 'Trilha de GPS salva com sucesso.']);
    }
}
