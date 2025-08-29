<?php
// Em app/Http/Middleware/IsSuperAdmin.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verifica se o utilizador está logado E se ele é um super-admin.
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            // 2. Se for, permite que o pedido continue para o seu destino.
            return $next($request);
        }

        // 3. Se não for, aborta o pedido e mostra uma página de erro "403 Acesso Negado".
        abort(403, 'Acesso Negado.');
    }
}