<?php

namespace App\Http\Controllers\Candidato;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RecursoController extends Controller
{
    /**
     * Mostra o formulário para o candidato interpor um recurso de CLASSIFICAÇÃO.
     */
    public function create()
    {
        $candidato = Auth::user()->candidato;

        // A verificação de segurança continua aqui, usando o accessor do Model.
        if (!$candidato || !$candidato->pode_interpor_recurso) {
            return redirect()->route('dashboard')->with('error', 'Não há recurso disponível para sua inscrição no momento.');
        }

        // ✅ LÓGICA ADICIONADA: Calcular o prazo final para exibir na tela.
        // Esta lógica é uma cópia da que está no Model, garantindo consistência.
        $prazoFinal = $candidato->homologado_em->copy();
        $diasUteisParaAdicionar = 2;
        
        while ($diasUteisParaAdicionar > 0) {
            $prazoFinal->addDay();
            if (!$prazoFinal->isWeekend()) {
                $diasUteisParaAdicionar--;
            }
        }
        $prazoFinal->setTime(17, 0, 0);

        // Passa tanto o candidato quanto o prazo final para a view.
        return view('candidato.recurso.create', compact('candidato', 'prazoFinal'));
    }

    /**
     * Armazena o recurso de CLASSIFICAÇÃO enviado pelo candidato num HISTÓRICO.
     */
    public function store(Request $request)
    {
        $request->validate([
            'recurso_texto' => 'required|string|min:50',
        ]);

        $candidato = Auth::user()->candidato;

        // Validação de segurança unificada. Perfeita, sem alterações.
        if (!$candidato || !$candidato->pode_interpor_recurso) {
            return redirect()->route('dashboard')->with('error', 'O prazo para enviar o recurso já encerrou ou a condição não é mais válida.');
        }

        // Pega o histórico existente ou inicia um array vazio.
        $historico = $candidato->recurso_historico ?? [];

        // Cria a nova entrada para o histórico.
        $novoRecurso = [
            'data_envio'          => now()->toDateTimeString(),
            'tipo'                => 'classificacao',
            'argumento_candidato' => $request->input('recurso_texto'),
            'decisao_admin'       => null,
            'justificativa_admin' => null,
        ];

        // Adiciona o novo recurso ao início do histórico (array_unshift).
        array_unshift($historico, $novoRecurso);

        // Atualiza os campos do candidato.
        $candidato->recurso_historico = $historico;
        $candidato->recurso_status    = 'em_analise';
        
        // Limpa os campos antigos para evitar confusão futura.
        $candidato->recurso_texto = null;
        $candidato->recurso_tipo  = null;
        
        $candidato->save();

        return redirect()->route('candidato.recurso.create')->with('success', 'Seu recurso foi enviado com sucesso e será analisado pela Comissão.');
    }
}