<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CandidatoAtividade;
use Illuminate\Http\Request;

class AtividadeAnaliseController extends Controller
{
    /**
     * Aprova uma atividade de pontuação específica.
     */
    public function aprovar(CandidatoAtividade $atividade)
    {
        // Limpa o motivo da rejeição caso a atividade esteja a ser re-aprovada.
        $atividade->update([
            'status' => 'Aprovada',
            'motivo_rejeicao' => null
        ]);

        return back()->with('success', 'Atividade aprovada com sucesso!');
    }

    /**
     * Rejeita uma atividade de pontuação específica com uma justificativa.
     */
    public function rejeitar(Request $request, CandidatoAtividade $atividade)
    {
        // ✅ VALIDA SE O MOTIVO FOI ENVIADO E NÃO ESTÁ VAZIO
        $request->validate([
            'motivo_rejeicao' => 'required|string|min:10',
        ]);

        // ✅ ATUALIZA O STATUS E GUARDA O MOTIVO REJEIÇÃO
        $atividade->update([
            'status' => 'Rejeitada',
            'motivo_rejeicao' => $request->motivo_rejeicao,
        ]);

        return back()->with('success', 'Atividade rejeitada com sucesso!');
    }
}