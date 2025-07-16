<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // Corrigido: use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
        'detalhes',
        'valor_bolsa_auxilio',
        'valor_auxilio_transporte',
        'requisitos',
        'beneficios',
        'carga_horaria',
        'local_estagio',
        'icone_svg', // ✅ ADICIONADO: Coluna para o SVG do ícone
    ];

    // ✅ REMOVIDO: O relacionamento instituicao() não pertence mais ao Model Curso,
    // pois a coluna instituicao_id foi removida da tabela cursos.
    // Este relacionamento agora pertence ao Model Candidato.
    /*
    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class);
    }
    */
}