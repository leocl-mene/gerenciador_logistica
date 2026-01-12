<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LocationController extends Controller
{
    public function onlineMotoboys()
    {
        $motoboys = User::whereIn('cargo_id', [User::ROLE_MOTORISTA, User::ROLE_ADMIN])
            ->whereNotNull(['ultima_latitude', 'ultima_longitude'])
            ->whereNotNull('ultimo_update')
            ->where('ultimo_update', '>=', Carbon::now()->subDay())
            ->get([
                'id',
                'name',
                'ultima_latitude',
                'ultima_longitude',
                'status_online',
                'ultimo_update',
            ]);

        return response()->json($motoboys);
    }
}
