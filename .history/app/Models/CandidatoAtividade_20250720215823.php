<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidatoAtividade extends Model
{
    use HasFactory;

    /**
     * ✅ AJUSTE: O array $fillable foi corrigido e completado.
     */
    protected $fillable = [
        'user_id',
        'candidato_id', 
        'tipo_de_atividade_id',
        'descricao_customizada',
        'carga_horaria',
        'data_inicio',
        'data_fim',
        'comprovativo_path', // Corrigido de 'path' para o nome correto
        'status',
        'motivo_rejeicao',
        'semestres_declarados', 
        'media_declarada_atividade',
        'prazo_recurso_ate', // Adicionado o campo de prazo
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'semestres_declarados' => 'integer', 
        'media_declarada_atividade' => 'float',
        'prazo_recurso_ate' => 'datetime', // ✅ ADICIONADO: Cast para o prazo
    ];

    /**
     * ✅ AJUSTE: Adicionada a nova relação principal com Candidato.
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