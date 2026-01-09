<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User; // Import adicionado
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MotoboyController extends Controller
{
    /**
     * Retorna a lista de veículos associados ao motoboy autenticado (Rota /api/meus-veiculos).
     * Usado pelo aplicativo mobile.
     */
    public function meusVeiculos()
    {
        // Acessa o usuário autenticado e seu relacionamento 'veiculos'
        $user = Auth::user();
        if ($user->cargo_id == User::ROLE_ADMIN) {
            return response()->json(Veiculo::orderBy('modelo')->get());
        }

        return response()->json($user->veiculos);
    }

    /**
     * Retorna os veículos associados a um motoboy específico (Rota /api/motoboys/{user}/veiculos).
     * Usado por rotas auxiliares ou pelo painel web.
     */
    public function getVeiculos(User $user)
    {
        // Retorna a lista de veículos do usuário em formato JSON
        return response()->json($user->veiculos);
    }
}