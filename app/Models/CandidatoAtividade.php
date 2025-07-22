<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidatoAtividade extends Model
{
    use HasFactory;

    /**
     * ✅ CORRIGIDO: O array $fillable foi ajustado para usar 'path' e incluir todos os campos necessários.
     */
    protected $fillable = [
        'user_id',
        'candidato_id', 
        'tipo_de_atividade_id',
        'descricao_customizada',
        'carga_horaria',
        'data_inicio',
        'data_fim',
        'path', // Nome correto da coluna do ficheiro
        'status',
        'motivo_rejeicao',
        'semestres_declarados', 
        'media_declarada_atividade',
        'prazo_recurso_ate',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'semestres_declarados' => 'integer', 
        'media_declarada_atividade' => 'float', 
        'prazo_recurso_ate' => 'datetime',
    ];

    /**
     * Uma atividade agora pertence a um Candidato.
     */
    public function candidato()
    {
        return $this->belongsTo(Candidato::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tipoDeAtividade()
    {
        return $this->belongsTo(TipoDeAtividade::class);
    }
}