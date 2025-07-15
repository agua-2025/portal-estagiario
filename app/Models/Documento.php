<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'tipo_documento',
        'path',
        'nome_original',
        'status',
    ];

    /**
     * Define a relação de que um Documento pertence a um Usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
