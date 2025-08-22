<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],

            // 🔒 ANTI-BOT (mínimo e sem atrito)
            'website' => ['max:0'], // honeypot: se vier preenchido, rejeita
            '_start' => ['required', 'integer', function ($attr, $value, $fail) {
                if (time() - (int) $value < 4) {
                    $fail('Envio muito rápido. Tente novamente.');
                }
            }],
        ], [
            'terms.required' => 'Você deve aceitar os Termos de Uso e Política de Privacidade.',
            'terms.accepted' => 'Você deve aceitar os Termos de Uso e Política de Privacidade.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'candidato',
            'terms_accepted_at' => now(),
        ]);

        // ✅ Garante que o role 'candidato' exista e atribui
        $candidatoRole = Role::firstOrCreate([
            'name' => 'candidato',
            'guard_name' => 'web'
        ]);
        $user->assignRole($candidatoRole);
        Log::info("Papel 'candidato' atribuído ao usuário: " . $user->email);

        event(new Registered($user));
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
