<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CandidatoAtividade;
use Illuminate\Http\Request;
use Carbon\Carbon; // ✅ ADICIONADO: Necessário para manipulação de datas

class AtividadeAnaliseController extends Controller
{
    /**
     * Aprova uma atividade de pontuação específica.
     */
    public function aprovar(CandidatoAtividade $atividade)
    {
        // ✅ AJUSTADO: Limpa também o prazo de recurso ao aprovar.
        $atividade->update([
            'status' => 'Aprovada',
            'motivo_rejeicao' => null,
            'prazo_recurso_ate' => null 
        ]);

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

        // ✅ AJUSTADO: Adiciona a lógica para calcular e salvar o prazo de recurso.
        $atividade->status = 'Rejeitada';
        $atividade->motivo_rejeicao = $request->motivo_rejeicao;

        // Verifica se a atividade é pontuável para definir o prazo de recurso
        if ($atividade->tipoDeAtividade && $atividade->tipoDeAtividade->pontos_por_unidade > 0) {
            // Se for pontuável, calcula e define o prazo de 2 dias úteis.
            $atividade->prazo_recurso_ate = $this->calcularDiasUteis(2);
        } else {
            // Se não for pontuável, não há prazo de recurso.
            $atividade->prazo_recurso_ate = null;
        }

        $atividade->save(); // Salva as alterações

        return back()->with('success', 'Atividade rejeitada com sucesso!');
    }

    /**
     * ✅ ADICIONADO: Método auxiliar para calcular uma data futura adicionando dias úteis.
     *
     * @param int $diasUteisParaAdicionar O número de dias úteis a serem adicionados.
     * @return Carbon
     */
    private function calcularDiasUteis(int $diasUteisParaAdicionar): Carbon
    {
        $data = Carbon::now();
        $diasAdicionados = 0;

        while ($diasAdicionados < $diasUteisParaAdicionar) {
            $data->addDay();
            // isWeekday() retorna true para Seg-Sex e false para Sab-Dom.
            if ($data->isWeekday()) {
                $diasAdicionados++;
            }
        }
        
        // Define o horário final do prazo para o final do dia.
        return $data->setTime(17, 0, 0); // Define às 17h00
    }
}