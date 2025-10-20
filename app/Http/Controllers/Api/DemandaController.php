<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Demanda;
use App\Models\DemandasFotosKm;
use App\Models\DemandaGpsTrack; // IMPORT ADICIONADO
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; // IMPORT ADICIONADO

class DemandaController extends Controller
{
    /**
     * Retorna uma lista de demandas com status 'Pendente'.
     */
    public function index()
    {
        $demandas = Demanda::where('status', 'Pendente')
                            ->with('percursos') // Carrega os endereços de percurso junto
                            ->latest() // As mais recentes primeiro
                            ->get();

        return response()->json($demandas);
    }

    /**
     * Associa a demanda ao motoboy logado e atualiza seu status.
     */
    public function aceitar(Request $request, Demanda $demanda)
    {
        // 1. Verifica se a demanda ainda está pendente
        if ($demanda->status !== 'Pendente') {
            return response()->json(['message' => 'Esta demanda não está mais disponível.'], 409); // 409 Conflict
        }

        // 2. Atualiza a demanda com o ID do motoboy e o novo status
        $demanda->motoboy_id = Auth::id(); // Pega o ID do motoboy autenticado pelo token
        $demanda->status = 'Aceita';
        $demanda->data_aceite = now(); // Salva a data e hora do aceite
        $demanda->save();

        return response()->json([
            'message' => 'Demanda aceita com sucesso!',
            'demanda' => $demanda->load('percursos') // Retorna a demanda atualizada
        ]);
    }

    /**
     * Inicia a demanda, salva a foto do KM inicial e muda o status para 'Em Rota'.
     */
    public function iniciar(Request $request, Demanda $demanda)
    {
        // 1. Validação: garante que um arquivo de imagem e o KM foram enviados
        $request->validate([
            'foto_km_inicio' => 'required|image|max:5120', // Imagem de até 5MB
            'km_inicial' => 'required|integer|min:0', // <-- NOVA VALIDAÇÃO
        ]);

        // 2. Garante que a demanda foi aceita por este motoboy
        if ($demanda->motoboy_id !== Auth::id() || $demanda->status !== 'Aceita') {
            return response()->json(['message' => 'Ação não autorizada.'], 403);
        }

        // 3. Salva a imagem no servidor
        $path = $request->file('foto_km_inicio')->store('public/km_fotos');

        // 4. Salva o caminho da foto no banco de dados
        DemandasFotosKm::create([
            'demanda_id' => $demanda->id,
            'foto_url_inicio' => Storage::url($path), // Salva a URL pública da foto
        ]);

        // 5. Atualiza o status da demanda e SALVA O KM INICIAL
        $demanda->status = 'Em Rota';
        $demanda->km_inicial = $request->km_inicial; // <-- SALVANDO O KM INICIAL
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
        
        // Verifica se o KM final é maior que o inicial (medida de segurança)
        if ($request->km_final < $demanda->km_inicial) {
             return response()->json(['message' => 'O KM final não pode ser menor que o KM inicial.'], 422);
        }

        // Salva a imagem final
        $path = $request->file('foto_km_final')->store('public/km_fotos');

        // Encontra o registro de fotos e atualiza com a URL da foto final
        $registroFotos = DemandasFotosKm::where('demanda_id', $demanda->id)->first();
        if ($registroFotos) {
            $registroFotos->foto_url_final = Storage::url($path);
            $registroFotos->save();
        }

        // Atualiza a demanda
        $demanda->status = 'Finalizada';
        $demanda->data_finalizacao = now();
        $demanda->km_final = $request->km_final; // <-- SALVANDO O KM FINAL
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
    
    // ===================================================================
    // FUNÇÃO 'iniciarUrgente' ATUALIZADA
    // ===================================================================
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

        // Se houver forma correta de descobrir a secretaria, use-a aqui:
        // $secretariaId = optional(Auth::user()->secretaria)->id; // exemplo
        // Caso contrário, deixe null (desde que a coluna permita):
        $secretariaId = null;

        try {
            $demanda = \App\Models\Demanda::create([
                'titulo'        => 'Demanda Urgente - ' . now()->format('d/m/Y H:i'),
                'tipo'          => 'urgente',
                'status'        => 'Em Rota',
                'secretaria_id' => $secretariaId, // remova se a coluna não existir
                'motoboy_id'    => Auth::id(),
                'veiculo_id'    => $request->veiculo_id,
                'km_inicial'    => $request->km_inicial,
                'data_aceite'   => now(),
            ]);

            $path = $request->file('foto_km_inicio')->store('public/km_fotos');

            \App\Models\DemandasFotosKm::create([
                'demanda_id'      => $demanda->id,
                'foto_url_inicio' => \Storage::url($path),
            ]);

            return response()->json([
                'message' => 'Demanda urgente iniciada!',
                'demanda' => $demanda
            ], 200);
        } catch (\Throwable $e) {
            // Vai te mostrar exatamente o problema no app (FK, fillable, etc.)
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
        // Reutiliza a mesma lógica de finalização, que já trata validações e segurança.
        return $this->finalizar($request, $demanda);
    }

    /**
     * Salva os pontos de rastreamento de GPS enviados pelo aplicativo.
     */
    public function storeTrack(Request $request, Demanda $demanda)
    {
        // Validação: 'track' deve ser um array
        $request->validate(['track' => 'required|array']);

        // Autorização: Apenas o motoboy atribuído pode enviar a trilha
        if ($demanda->motoboy_id !== Auth::id()) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        // Itera sobre o array de pontos e salva cada um
        foreach ($request->track as $point) {
            // Garante que lat, lon e timestamp estão presentes
            if (!isset($point['latitude']) || !isset($point['longitude']) || !isset($point['timestamp'])) {
                continue; // Pula pontos inválidos
            }
            
            DemandaGpsTrack::create([
                'demanda_id' => $demanda->id,
                'latitude' => $point['latitude'],
                'longitude' => $point['longitude'],
                // Converte o timestamp da string do Flutter para um objeto Carbon
                'recorded_at' => Carbon::parse($point['timestamp']), 
            ]);
        }

        return response()->json(['message' => 'Trilha de GPS salva com sucesso.']);
    }
}