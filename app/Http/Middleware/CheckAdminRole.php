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
        // Verifica se o usuário está autenticado
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // Verifica o role de duas formas:
        // 1. Se estiver usando Spatie Permission
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('admin')) {
                return $next($request);
            }
        }
        
        // 2. Se estiver usando campo 'role' direto na tabela users
        if (isset($user->role) && $user->role === 'admin') {
            return $next($request);
        }

        // Se não for admin, redireciona para o dashboard normal
        // com uma mensagem de erro opcional
        return redirect('/dashboard')
            ->with('error', 'Você não tem permissão para acessar esta área.');
    }
}