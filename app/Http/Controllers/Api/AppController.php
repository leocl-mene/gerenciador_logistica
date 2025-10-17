<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function latestVersion()
    {
        // IMPORTANTE: Você vai atualizar este número manualmente toda vez que gerar um novo APK.
        $latestVersion = '1.0.1'; // Exemplo da próxima versão

        return response()->json([
            'version' => $latestVersion,
            'url' => url('/downloads/app-release.apk'), // Gera a URL completa para o download
        ]);
    }
}