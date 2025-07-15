<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instituicao extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'instituicoes';
    protected $fillable = [
        'nome',
        'sigla',
        'endereco',
        'cidade',
        'estado',
        'telefone_contato',
    ];
}