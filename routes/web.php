<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Secretaria\MotoboyController;
use App\Http\Controllers\Secretaria\AdminController;
use App\Http\Controllers\Secretaria\DemandaController;
use App\Http\Controllers\Secretaria\RelatorioController;
use App\Http\Controllers\Secretaria\SettingController; // Importação adicionada
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return view('welcome');
});

// Rotas de autenticação (perfil) que se aplicam a todos os usuários logados
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- NOSSAS ROTAS DE DASHBOARD POR CARGO ---

// Rotas para Administrador (acessível apenas por cargo:1)
Route::middleware(['auth', 'cargo:1'])->group(function () {
    Route::get('/dashboard-secretaria', function () {
        return view('dashboard-secretaria');
    })->name('dashboard.secretaria');

    // Rota de resource para Veículos, acessível pela Secretaria
    Route::resource('veiculos', App\Http\Controllers\VeiculoController::class);

    // Rota de resource para Motoristas, acessível pelo Administrador
    Route::resource('motoboys', MotoboyController::class);

    // Rotas para gerenciar a associação de veículos a um motoboy (NOVAS ROTAS)
    Route::get('/motoboys/{motoboy}/veiculos', [MotoboyController::class, 'gerenciarVeiculos'])->name('motoboys.veiculos.gerenciar');
    Route::post('/motoboys/{motoboy}/veiculos', [MotoboyController::class, 'salvarVeiculos'])->name('motoboys.veiculos.salvar');

    // Rota de resource para Administradores
    Route::resource('administradores', AdminController::class)->parameters([
        'administradores' => 'administrador',
    ]);

    // Rota de resource para Demandas, acessível pela Secretaria
    Route::resource('demandas', DemandaController::class);

    // Rotas para Geração de Relatórios
    Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
    Route::get('/relatorios/gerar', [RelatorioController::class, 'generate'])->name('relatorios.generate');
    Route::get('/relatorios/abastecimentos', [RelatorioController::class, 'abastecimentos'])->name('relatorios.abastecimentos');

    // ROTA DE CONFIGURAÇÕES
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
});

// Rotas para Motorista (acessível apenas por cargo:2)
Route::middleware(['auth', 'cargo:2'])->group(function () {
    Route::get('/dashboard-motoboy', function () {
        return view('dashboard-motoboy');
    })->name('dashboard.motoboy');
});

// ROTA DE TESTE ADICIONADA AQUI
Route::get('/test-log', function () {
    Log::info('Este é um teste para o arquivo de log.');
    return 'Mensagem de log enviada! Verifique o arquivo storage/logs/laravel.log';
});

require __DIR__.'/auth.php';
