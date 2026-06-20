<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário autenticado via Sanctum possui o cargo de Administrador
        if (auth()->check() && auth()->user()->tipo_conta === 'Administrador') {
            return $next($request);
        }

        // Se não for Admin, barra a requisição imediatamente
        return response()->json([
            'message' => 'Acesso negado. Esta área é exclusiva para Administradores.'
        ], 403); // 403 = Forbidden
    }
}