<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User; // Necessário para contar administradores
use Illuminate\Support\Facades\DB; // Necessário para transações
use Illuminate\Validation\ValidationException; // Necessário para validateWithBag

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // MUDEI AQUI: Usando validateWithBag para que a validação vá para a bag 'userDeletion'
        try {
            $request->validateWithBag('userDeletion', [
                'password' => ['required', 'current_password'],
            ]);
        } catch (ValidationException $e) {
            // Se a validação da senha falhar, os erros já estarão na bag 'userDeletion'
            // O modal será reaberto automaticamente se houver erros nessa bag
            return Redirect::back()->withErrors($e->validator->errors()->getBags());
        }

        $user = $request->user();

        // 1. Lógica para prevenir que o único admin se auto-apague
        // Verifica se o usuário que está sendo apagado é um admin
        if ($user->hasRole('admin')) {
            // Conta quantos usuários com papel 'admin' existem
            $adminCount = User::role('admin')->count();

            // Se for o único admin, impede a exclusão
            if ($adminCount <= 1) {
                // Mensagem de erro agora está em 'general_error' dentro da bag 'userDeletion'
                return Redirect::back()->withErrors([
                    'general_error' => 'Você é o único administrador. Para apagar sua conta, nomeie outro administrador primeiro.'
                ], 'userDeletion')->withInput($request->except('password')); // Reabre o modal
            }
        }

        // Inicia uma transação de banco de dados
        DB::beginTransaction();

        try {
            // Apaga o perfil de candidato associado (se existir)
            if ($user->candidato) {
                $user->candidato->delete();
            }

            // Desloga o usuário antes de apagar a conta dele
            Auth::logout();

            // Apaga o usuário
            $user->delete();

            // Invalida a sessão e regenera o token
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            DB::commit(); // Confirma as operações no banco de dados
        } catch (\Exception $e) {
            DB::rollBack(); // Reverte as operações em caso de erro
            \Log::error("Erro ao apagar conta do usuário ID {$user->id}: " . $e->getMessage());
            // Se houver um erro geral na exclusão, pode ser retornado na bag 'userDeletion' também
            // E garante que o modal abra com withInput()
            return Redirect::back()->withErrors([
                'general_error' => 'Ocorreu um erro inesperado ao tentar apagar sua conta. Por favor, tente novamente.'
            ], 'userDeletion')->withInput($request->except('password'));
        }

        return Redirect::to('/');
    }
}