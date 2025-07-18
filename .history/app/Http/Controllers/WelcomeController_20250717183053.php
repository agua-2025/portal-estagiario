// app/Models/Candidato.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    use HasFactory;

    // ... (seus fillable, relacionamentos como user(), curso(), instituicao()) ...

    // Este método é APENAS UM EXEMPLO. Use a sua lógica real de cálculo de pontuação.
    public function calcularPontuacaoDetalhada()
    {
        // Sua lógica REAL de cálculo de pontuação.
        // Por exemplo, somar pontos de atividades, documentos, etc.
        // EXEMPLO SIMPLIFICADO:
        $total = 0;
        $detalhes = [];

        // Exemplo: Se você tivesse um relacionamento 'atividades' e cada atividade tivesse 'pontos_obtidos'
        // if ($this->relationLoaded('atividades')) {
        //     foreach ($this->atividades as $atividade) {
        //         $pontos = $atividade->pontos_obtidos; // Supondo que você tenha esses campos
        //         $total += $pontos;
        //         $detalhes[] = ['nome' => $atividade->tipo->nome ?? 'Atividade', 'pontos' => $pontos];
        //     }
        // }
        
        // Se a pontuação é salva na coluna 'pontuacao_final' mas você quer recalcular
        // ou garantir que a pontuação é sempre a mais recente baseada em outros campos:
        // $total = $this->pontuacao_final; // Se já está salvo e você confia nesse valor
        // $detalhes = json_decode($this->pontuacao_detalhes_json ?? '[]', true); // Se os detalhes forem salvos em JSON

        // A PARTIR DO SEU ClassificacaoController, o calcularPontuacaoDetalhada() JÁ EXISTE.
        // Garanta que ele retorna algo assim:
        return [
            'total' => $this->pontuacao_final_raw_do_banco ?? 0, // Ou o resultado do seu cálculo
            'detalhes' => [], // Ou os detalhes reais do cálculo
        ];
    }
}