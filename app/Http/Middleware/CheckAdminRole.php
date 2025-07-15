<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    // Verifica se o usuário está logado E se o papel dele é 'admin'
    if (auth()->check() && auth()->user()->role === 'admin') {
        // Se for admin, pode continuar e acessar a página
        return $next($request);
    }

    // Se não for admin, redireciona para o dashboard normal de candidato
    return redirect('/dashboard');
}
}
