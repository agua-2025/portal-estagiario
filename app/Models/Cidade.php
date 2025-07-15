<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    use HasFactory;

    /**
     * Indica que o model não deve ter timestamps (created_at e updated_at).
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = ['nome', 'estado_id'];

    /**
     * Define a relação de que uma Cidade pertence a um Estado.
     */
    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }
}