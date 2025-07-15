<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidatoAtividade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipo_de_atividade_id',
        'descricao_customizada',
        'carga_horaria',
        'data_inicio',
        'data_fim',
        'path',
        'status',
        'motivo_rejeicao',
        'semestres_declarados', // ✅ ADICIONADO: Nova coluna
        'media_declarada_atividade', // ✅ ADICIONADO: Nova coluna para a média
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'semestres_declarados' => 'integer', // ✅ ADICIONADO: Cast para a nova coluna
        'media_declarada_atividade' => 'float', // ✅ ADICIONADO: Cast para a nova coluna de média
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tipoDeAtividade()
    {
        return $this->belongsTo(TipoDeAtividade::class);
    }
}