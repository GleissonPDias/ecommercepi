<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
        {
    // 1. Checa se o usuário está logado E se ele é admin
            if (!auth()->check() || !auth()->user()->isAdmin()) {
        // 2. Se não for, redireciona para a home com um erro
             return redirect()->route('home')->with('error', 'Acesso negado.');
    }

    // 3. Se for admin, permite o acesso
             return $next($request);
}
}
