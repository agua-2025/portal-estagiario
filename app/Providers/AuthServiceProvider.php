<?php

namespace App\Providers;

use App\Models\CandidatoAtividade;
use App\Models\Documento; // ✅ Adicionar esta linha
use App\Policies\CandidatoAtividadePolicy;
use App\Policies\DocumentoPolicy; // ✅ Adicionar esta linha
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        CandidatoAtividade::class => CandidatoAtividadePolicy::class,
        Documento::class => DocumentoPolicy::class, // ✅ Adicionar esta linha
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}

