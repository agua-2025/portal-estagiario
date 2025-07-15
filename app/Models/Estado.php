<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    /**
     * Indica que o model não deve ter timestamps (created_at e updated_at).
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Define a relação de que um Estado tem muitas Cidades.
     */
    public function cidades()
    {
        return $this->hasMany(Cidade::class);
    }
}