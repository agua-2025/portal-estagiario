<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider; // Importado para redirecionamento
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role; // ✅ ADICIONADO: Importar o modelo Role do Spatie
use Illuminate\Support\Facades\Log; // ✅ ADICIONADO: Para logar avisos, se o papel não for encontrado

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'], // Para garantir que os termos foram aceitos
        ], [
            'terms.required' => 'Você deve aceitar os Termos de Uso e Política de Privacidade.',
            'terms.accepted' => 'Você deve aceitar os Termos de Uso e Política de Privacidade.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'candidato', // Manter para compatibilidade com sua lógica antiga
            'terms_accepted_at' => now(), // Registra a data de aceitação dos termos
        ]);

        // ✅ ADICIONADO: Atribuir o papel 'estagiario' via Spatie
        // Busca o papel 'estagiario'. É importante que este papel já tenha sido criado pelo seeder!
        $estagiarioRole = Role::findByName('estagiario'); 
        if ($estagiarioRole) {
            $user->assignRole($estagiarioRole);
        } else {
            // Se por algum motivo o papel 'estagiario' não foi encontrado (o que não deveria acontecer se o seeder rodou)
            Log::warning('Papel "estagiario" não encontrado ao registrar novo usuário. Verifique o seeder.');
        }

        event(new Registered($user));

        Auth::login($user);

        // O RouteServiceProvider::HOME geralmente aponta para /dashboard
        return redirect(RouteServiceProvider::HOME); 
    }
}