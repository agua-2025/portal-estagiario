<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CandidatoAtividade;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AtividadeAnaliseController extends Controller
{
    /**
     * Aprova uma atividade de pontuação específica.
     */
    public function aprovar(CandidatoAtividade $atividade)
    {
        $atividade->update([
            'status' => 'Aprovada',
            'motivo_rejeicao' => null,
            'prazo_recurso_ate' => null 
        ]);

        // ✅ GATILHO ADICIONADO AQUI:
        // Após aprovar a atividade, recalcula e salva a pontuação total do candidato.
        $atividade->candidato->updateAndSaveScore();

        return back()->with('success', 'Atividade aprovada com sucesso!');
    }

    /**
     * Rejeita uma atividade de pontuação específica com uma justificativa.
     */
    public function rejeitar(Request $request, CandidatoAtividade $atividade)
    {
        $request->validate([
            'motivo_rejeicao' => 'required|string|min:10',
        ]);

        $atividade->status = 'Rejeitada';
        $atividade->motivo_rejeicao = $request->motivo_rejeicao;

        if ($atividade->tipoDeAtividade && $atividade->tipoDeAtividade->pontos_por_unidade > 0) {
            $atividade->prazo_recurso_ate = $this->calcularDiasUteis(2);
        } else {
            $atividade->prazo_recurso_ate = null;
        }

        $atividade->save();

        // ✅ GATILHO ADICIONADO AQUI:
        // Após rejeitar a atividade, também recalcula e salva a pontuação (que pode ter diminuído).
        $atividade->candidato->updateAndSaveScore();

        return back()->with('success', 'Atividade rejeitada com sucesso!');
    }

    /**
     * Método auxiliar para calcular uma data futura adicionando dias úteis.
     */
    private function calcularDiasUteis(int $diasUteisParaAdicionar): Carbon
    {
        $data = Carbon::now();
        $diasAdicionados = 0;

        while ($diasAdicionados < $diasUteisParaAdicionar) {
            $data->addDay();
            if ($data->isWeekday()) {
                $diasAdicionados++;
            }
        }
        
        return $data->setTime(17, 0, 0);
    }
}