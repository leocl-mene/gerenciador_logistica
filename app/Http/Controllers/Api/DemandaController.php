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
    /**
     * Retorna uma lista de demandas com status 'Pendente'.
     */
    public function index()
    {
        $demandas = Demanda::where('status', 'Pendente')
                            ->with('percursos')
                            ->latest()
                            ->get();

        return response()->json($demandas);
    }

    /**
     * Associa a demanda ao motoboy logado e atualiza seu status.
     */
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
            'demanda' => $demanda->load('percursos')
        ]);
    }

    /**
     * Inicia a demanda, salva a foto do KM inicial e muda o status para 'Em Rota'.
     */
    public function iniciar(Request $request, Demanda $demanda)
    {
        $request->validate([
            'foto_km_inicio' => 'required|image|max:5120',
            'km_inicial' => 'required|integer|min:0',
        ]);

        if ($demanda->motoboy_id !== Auth::id() || $demanda->status !== 'Aceita') {
            return response()->json(['message' => 'Ação não autorizada.'], 403);
        }

        // --- LÓGICA DE ARMAZENAMENTO DE FOTO ATUALIZADA ---
        $path = $request->file('foto_km_inicio')->store('km_fotos', 'public');
        $url  = Storage::disk('public')->url($path);

        DemandasFotosKm::create([
            'demanda_id' => $demanda->id,
            'foto_url_inicio' => $url,
        ]);
        // --- FIM DA ATUALIZAÇÃO ---

        $demanda->status = 'Em Rota';
        $demanda->km_inicial = $request->km_inicial;
        $demanda->save();

        return response()->json(['message' => 'Demanda iniciada com sucesso!']);
    }

    /**
     * Finaliza a demanda, salva a foto do KM final e muda o status para 'Finalizada'.
     */
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

        // --- LÓGICA DE ARMAZENAMENTO DE FOTO ATUALIZADA ---
        $path = $request->file('foto_km_final')->store('km_fotos', 'public');
        $url  = Storage::disk('public')->url($path);

        $registroFotos = DemandasFotosKm::where('demanda_id', $demanda->id)->first();
        if ($registroFotos) {
            $registroFotos->foto_url_final = $url;
            $registroFotos->save();
        }
        // --- FIM DA ATUALIZAÇÃO ---

        $demanda->status = 'Finalizada';
        $demanda->data_finalizacao = now();
        $demanda->km_final = $request->km_final;
        $demanda->save();

        return response()->json(['message' => 'Demanda finalizada com sucesso!']);
    }

    /**
     * Retorna as demandas atribuídas ao motoboy logado (Aceitas ou Em Rota).
     */
    public function minhasDemandas()
    {
        $demandas = Demanda::where('motoboy_id', Auth::id())
                            ->whereIn('status', ['Aceita', 'Em Rota'])
                            ->with('percursos')
                            ->latest()
                            ->get();

        return response()->json($demandas);
    }
    
    /**
     * Cria e inicia uma demanda urgente em um único passo.
     */
    public function iniciarUrgente(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'veiculo_id'     => 'required|exists:veiculos,id',
            'km_inicial'     => 'required|integer|min:0',
            'foto_km_inicio' => 'required|image|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $secretariaId = null;

        try {
            $demanda = \App\Models\Demanda::create([
                'titulo'        => 'Demanda Urgente - ' . now()->format('d/m/Y H:i'),
                'tipo'          => 'urgente',
                'status'        => 'Em Rota',
                'secretaria_id' => $secretariaId,
                'motoboy_id'    => Auth::id(),
                'veiculo_id'    => $request->veiculo_id,
                'km_inicial'    => $request->km_inicial,
                'data_aceite'   => now(),
            ]);

            // --- LÓGICA DE ARMAZENAMENTO DE FOTO ATUALIZADA ---
            $path = $request->file('foto_km_inicio')->store('km_fotos', 'public');
            $url  = \Storage::disk('public')->url($path);

            \App\Models\DemandasFotosKm::create([
                'demanda_id'      => $demanda->id,
                'foto_url_inicio' => $url,
            ]);
            // --- FIM DA ATUALIZAÇÃO ---

            return response()->json([
                'message' => 'Demanda urgente iniciada!',
                'demanda' => $demanda
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Falha ao iniciar demanda urgente.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Finaliza a demanda urgente. Reutiliza a lógica de finalização normal.
     */
    public function finalizarUrgente(Request $request, Demanda $demanda)
    {
        return $this->finalizar($request, $demanda);
    }

    /**
     * Salva os pontos de rastreamento de GPS enviados pelo aplicativo.
     */
    public function storeTrack(Request $request, Demanda $demanda)
    {
        $request->validate(['track' => 'required|array']);

        if ($demanda->motoboy_id !== Auth::id()) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        foreach ($request->track as $point) {
            if (!isset($point['latitude']) || !isset($point['longitude']) || !isset($point['timestamp'])) {
                continue;
            }
            
            DemandaGpsTrack::create([
                'demanda_id' => $demanda->id,
                'latitude' => $point['latitude'],
                'longitude' => $point['longitude'],
                'recorded_at' => Carbon::parse($point['timestamp']), 
            ]);
        }

        return response()->json(['message' => 'Trilha de GPS salva com sucesso.']);
    }
}
