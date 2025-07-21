<?php

namespace App\Http\Controllers\Candidato;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RecursoController extends Controller
{
    /**
     * Mostra a página com o formulário para o candidato interpor um recurso.
     */
    public function create()
    {
        $candidato = Auth::user()->candidato;

        // Medida de segurança: só permite acesso se a inscrição estiver de fato rejeitada.
        if (!$candidato || $candidato->status !== 'Rejeitado') {
            return redirect()->route('dashboard')->with('error', 'Não há recurso disponível para sua inscrição no momento.');
        }

        return view('candidato.recurso.create', compact('candidato'));
    }

    /**
     * Armazena o recurso enviado pelo candidato.
     */
    public function store(Request $request)
    {
        $request->validate([
            'recurso_texto' => 'required|string|min:50',
        ]);

        $user = Auth::user();
        $candidato = $user->candidato;

        // Validações de segurança
        if (!$candidato || $candidato->status !== 'Rejeitado') {
            return redirect()->route('dashboard')->with('error', 'Não há recurso pendente para esta inscrição.');
        }

        if (Carbon::now()->gt($candidato->recurso_prazo_ate)) {
            return redirect()->route('dashboard')->with('error', 'O prazo para enviar o recurso já encerrou.');
        }

        // Salva o recurso e atualiza os status
        $candidato->recurso_texto = $request->input('recurso_texto');
        $candidato->recurso_status = 'em_analise';
        $candidato->status = 'Em Análise'; // Devolve para a fila de análise do admin
        $candidato->save();

        return redirect()->route('dashboard')->with('success', 'Seu recurso foi enviado com sucesso e será analisado pela Comissão.');
    }
}