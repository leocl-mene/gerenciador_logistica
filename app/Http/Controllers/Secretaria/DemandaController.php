<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Demanda;
use App\Models\DemandaPercurso;
use App\Models\DemandasFotosKm;
use App\Models\Veiculo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

// Imports do Firebase Cloud Messaging (FCM)
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;

class DemandaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Carrega todas as demandas (incluindo as urgentes) com os relacionamentos
        $demandas = Demanda::with(['secretaria', 'motoboy', 'percursos', 'veiculo'])
                            ->latest()
                            ->get();
        return view('secretaria.demandas.index', compact('demandas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Pega todos os usuários que são motoboys para popular o dropdown
        $motoboys = User::where('cargo_id', 3)->orderBy('name')->get();
        $veiculos = Veiculo::orderBy('modelo')->get();

        return view('secretaria.demandas.create', compact('motoboys', 'veiculos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'motoboy_id' => 'required|exists:users,id',
            'veiculo_id' => 'required|exists:veiculos,id',
            'percursos' => 'required|array|min:2',
            'percursos.*' => 'required|string|max:255',
        ]);

        // 1. Cria a demanda principal, ATRIBUINDO-A IMEDIATAMENTE
        $demanda = Demanda::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'is_priority' => $request->has('is_priority'),
            'secretaria_id' => Auth::id(),
            'motoboy_id' => $request->motoboy_id,
            'veiculo_id' => $request->veiculo_id,
            'status' => 'Aceita',
            'tipo' => 'normal', 
            'data_aceite' => Carbon::now(),
        ]);

        // 2. Salva cada ponto do percurso com geocodificação
        foreach ($request->percursos as $index => $endereco) {
            $response = Http::get('https://nominatim.openstreetmap.org/search', [
                'q' => $endereco,
                'format' => 'json',
                'countrycodes' => 'br',
                'limit' => 1
            ]);
            $location = $response->json()[0] ?? null;

            DemandaPercurso::create([
                'demanda_id' => $demanda->id,
                'ordem' => $index + 1,
                'endereco' => $endereco,
                'latitude' => $location['lat'] ?? null,
                'longitude' => $location['lon'] ?? null,
            ]);
        }
        
        // --- INÍCIO DA LÓGICA DE NOTIFICAÇÃO ---
        $this->sendNewDemandNotification($demanda);
        // --- FIM DA LÓGICA DE NOTIFICAÇÃO ---

        return redirect()->route('demandas.index')->with('success', 'Demanda atribuída com sucesso ao motoboy!');
    }
    
    /**
     * Envia notificação Firebase apenas para o motoboy atribuído à demanda.
     */
    private function sendNewDemandNotification(Demanda $demanda)
    {
        // 1. Busca o motoboy atribuído e seu token FCM
        $motoboy = $demanda->motoboy;

        if (!$motoboy || !$motoboy->fcm_token) {
            // Se não houver motoboy ou token, não faz nada
            return;
        }

        $messaging = app('firebase.messaging');
        
        // 2. Define o som e o título
        $soundFile = $demanda->is_priority ? 'urgent_sound.wav' : 'new_demand.mp3';
        $title = $demanda->is_priority ? 'URGENTE: Nova Atribuição!' : 'Nova Demanda Atribuída';

        // 3. Cria a Notificação e as Configurações Específicas de Plataforma
        $notification = Notification::create($title, $demanda->titulo . ' foi atribuída a você.');

        $message = CloudMessage::new()
            ->withNotification($notification)
            ->withData(['demanda_id' => (string)$demanda->id]) // Envia o ID da demanda nos dados
            // Android usa o nome do arquivo sem extensão, em minúsculas
            ->withAndroidConfig(AndroidConfig::new()->withSound(strtolower(str_replace('.mp3', '', $soundFile)))) 
            // iOS usa o nome completo do arquivo
            ->withApnsConfig(ApnsConfig::new()->withSound($soundFile)); 

        // 4. Envia a mensagem para o token específico
        try {
             $messaging->send($message->withchangedTargetTarget('token', $motoboy->fcm_token));
        } catch (\Throwable $e) {
            // Logar erro de envio se necessário
            // Log::error("FCM failed for user {$motoboy->id}: {$e->getMessage()}");
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Demanda $demanda)
    {
        // Carrega a demanda com seus percursos, o motoboy (se houver),
        // a secretaria que criou, e as fotos do KM.
        $demanda->load(['percursos', 'motoboy', 'secretaria', 'fotosKm', 'veiculo']);

        return view('secretaria.demandas.show', compact('demanda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Demanda $demanda)
    {
        $demanda->load('percursos');
        
        // Buscamos os dados para os dropdowns
        $motoboys = User::where('cargo_id', 3)->orderBy('name')->get();

        // Se a demanda já tem um motoboy, pegamos os veículos dele para pré-popular o select
        $veiculosDoMotoboy = $demanda->motoboy ? $demanda->motoboy->veiculos : collect();

        // Passamos os três objetos para a view
        return view('secretaria.demandas.edit', compact('demanda', 'motoboys', 'veiculosDoMotoboy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Demanda $demanda)
    {
        // ATUALIZAÇÃO: Adicionamos a validação para os novos campos
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'motoboy_id' => 'required|exists:users,id',
            'veiculo_id' => 'required|exists:veiculos,id',
            'percursos' => 'required|array|min:2',
            'percursos.*' => 'required|string|max:255',
        ]);
        
        // Verifica se o motoboy mudou
        $motoboyMudou = $demanda->motoboy_id != $request->motoboy_id;

        // ATUALIZAÇÃO: Adicionamos os novos campos ao update
        $demanda->update([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'is_priority' => $request->has('is_priority'),
            'motoboy_id' => $request->motoboy_id,
            'veiculo_id' => $request->veiculo_id,
        ]);

        // 2. Apaga e recria o percurso
        $demanda->percursos()->delete();

        foreach ($request->percursos as $index => $endereco) {
            $response = Http::get('https://nominatim.openstreetmap.org/search', [
                'q' => $endereco,
                'format' => 'json',
                'countrycodes' => 'br',
                'limit' => 1
            ]);
            $location = $response->json()[0] ?? null;

            DemandaPercurso::create([
                'demanda_id' => $demanda->id,
                'ordem' => $index + 1,
                'endereco' => $endereco,
                'latitude' => $location['lat'] ?? null,
                'longitude' => $location['lon'] ?? null,
            ]);
        }
        
        // Se o motoboy mudou durante a edição, envia uma notificação para ele.
        if ($motoboyMudou) {
            $this->sendNewDemandNotification($demanda);
        }

        return redirect()->route('demandas.index')->with('success', 'Demanda atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Demanda $demanda)
    {
        if (in_array($demanda->status, ['Aceita', 'Em Rota'])) {
            return back()->with('error', 'Não é possível excluir uma demanda que está em andamento.');
        }

        $demanda->delete();
        return redirect()->route('demandas.index')->with('success', 'Demanda excluída com sucesso.');
    }
}