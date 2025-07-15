<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDeAtividade extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao model.
     */
    protected $table = 'tipos_de_atividade';

    /**
     * Os atributos que são atribuíveis em massa.
     */
    protected $fillable = [
        'nome',
        'descricao',
        'unidade_medida',
        'pontos_por_unidade',
        'divisor_unidade',
        'pontuacao_maxima',
    ];

    /**
     * ✅ RELAÇÃO ADICIONADA
     * Uma regra de pontuação pode estar associada a muitas atividades de candidatos.
     */
    public function candidatoAtividades()
    {
        return $this->hasMany(CandidatoAtividade::class);
    }
}