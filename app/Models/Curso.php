<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'instituicao_id', // Campo existente
        'descricao',
        'detalhes',
        'valor_bolsa_auxilio',
        'valor_auxilio_transporte',
        'requisitos',
        'beneficios',
        'carga_horaria',
        'local_estagio',
    ];

    /**
     * Get the institution that owns the course.
     */
    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class);
    }
}