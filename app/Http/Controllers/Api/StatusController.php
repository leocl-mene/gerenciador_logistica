<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    /**
     * Atualiza o status online e a localização do motoboy.
     */
    public function update(Request $request)
    {
        $request->validate([
            'status_online' => 'required|boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $user = Auth::user();

        $user->status_online = $request->status_online;

        if ($request->has('latitude') && $request->has('longitude')) {
            $user->ultima_latitude = $request->latitude;
            $user->ultima_longitude = $request->longitude;
            $user->ultimo_update = now();
        }

        $user->save();

        return response()->json(['message' => 'Status atualizado com sucesso.']);
    }

    /**
     * Salva o token do Firebase Cloud Messaging (FCM) para notificações.
     */
    public function updateFcmToken(Request $request)
    {
        $request->validate(['fcm_token' => 'required|string']);
        
        // Atualiza o token FCM para o usuário autenticado
        Auth::user()->update(['fcm_token' => $request->fcm_token]);
        
        return response()->json(['message' => 'FCM token atualizado.']);
    }
}