<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Candidato;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'terms_accepted_at',
    ];

    protected $hidden = [ 'password', 'remember_token', ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'terms_accepted_at' => 'datetime',
        ];
    }

    public function candidato()
    {
        return $this->hasOne(Candidato::class);
    }

    // ✅ AJUSTE: As relações 'documentos()' e 'candidatoAtividades()' foram removidas
    // pois agora pertencem ao Model 'Candidato'.
}