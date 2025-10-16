<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function onlineMotoboys()
    {
        $motoboys = User::where('cargo_id', 3) // Apenas Motoboys
                          ->where('status_online', true) // Apenas os que estão online
                          ->whereNotNull(['ultima_latitude', 'ultima_longitude']) // Garante que eles têm uma localização válida
                          ->get(['id', 'name', 'ultima_latitude', 'ultima_longitude']); // Pega apenas os dados necessários

        return response()->json($motoboys);
    }
}