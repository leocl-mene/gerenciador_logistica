<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DemandaController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\MotoboyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rota para o Dashboard da Secretaria buscar os motoboys (pública por enquanto)
Route::get('/motoboys-online', [LocationController::class, 'onlineMotoboys']);

// Rota de Login para o aplicativo do motoboy (Pública)
Route::post('/login', [AuthController::class, 'login']);

// ADICIONADA: Rota para buscar veículos de um motoboy específico (usando o MotoboyController da API)
Route::get('/motoboys/{user}/veiculos', [MotoboyController::class, 'getVeiculos']);

// Grupo de rotas que exigem autenticação via token (para o app do motoboy)
Route::middleware('auth:sanctum')->group(function () {
    // Rota para deslogar
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Retorna os dados do usuário autenticado via token
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rota para listar veículos do motoboy
    Route::get('/meus-veiculos', [MotoboyController::class, 'meusVeiculos']);

    // Rota para listar demandas disponíveis para o motoboy
    Route::get('/demandas-disponiveis', [DemandaController::class, 'index']);

    // Rota para Listas Demandas do
    Route::get('/minhas-demandas', [DemandaController::class, 'minhasDemandas']);

    // ===================================================================
    // IMPORTANTE: Rotas específicas (com texto) devem vir ANTES
    // de rotas genéricas (com parâmetros como {demanda})
    // para evitar que o roteador se confunda.
    // ===================================================================

    // Rota para iniciar demanda urgente
    Route::post('/demandas/urgente/iniciar', [DemandaController::class, 'iniciarUrgente']);
    
    // Rota para finalizar demanda urgente
    Route::post('/demandas/{demanda}/urgente/finalizar', [DemandaController::class, 'finalizarUrgente']);
    
    // --- Rotas genéricas com {demanda} ---

    // Rota para um motoboy aceitar uma demanda
    Route::post('/demandas/{demanda}/aceitar', [DemandaController::class, 'aceitar']);

    // Rota para um motoboy iniciar uma demanda
    Route::post('/demandas/{demanda}/iniciar', [DemandaController::class, 'iniciar']);

    // Rota para um motoboy finalizar uma demanda
    Route::post('/demandas/{demanda}/finalizar', [DemandaController::class, 'finalizar']);
    
    // Rota para o motoboy enviar rastreamento de uma demanda
    Route::post('/demandas/{demanda}/track', [DemandaController::class, 'storeTrack']);

    // Rota para o motoboy atualizar seu status e localização
    Route::post('/status', [StatusController::class, 'update']);
    
    // Rota para atualizar o token FCM do usuário (NOVA ROTA)
    Route::post('/fcm-token', [StatusController::class, 'updateFcmToken']);
});
