<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Candidato;
use Spatie\Permission\Traits\HasRoles; // <-- ADICIONE ESTA LINHA

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles; // <-- ADICIONE 'HasRoles' AQUI

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
}