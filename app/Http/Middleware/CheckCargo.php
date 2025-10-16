<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCargo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$cargos): Response
    {
        $user = Auth::user();

        // Se o cargo do usuário não estiver na lista de cargos permitidos para a rota
        if (!$user || !in_array($user->cargo_id, $cargos)) {
            // Bloqueia o acesso com uma página de "Não Autorizado"
            abort(403, 'Acesso Não Autorizado.');
        }

        return $next($request);
    }
}
