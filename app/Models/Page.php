<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'pages'; // Define explicitamente o nome da tabela se não seguir a convenção de pluralização

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'title',
        'content',
    ];
}

