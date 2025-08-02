<?php

namespace App\Providers;

// ✅ Modelos
use App\Models\Candidato;
use App\Models\CandidatoAtividade;
use App\Models\Documento;

// ✅ Policies
use App\Policies\CandidatoPolicy;
use App\Policies\CandidatoAtividadePolicy;
use App\Policies\DocumentoPolicy;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // ✅ LINHA QUE FALTAVA
        Candidato::class => CandidatoPolicy::class,
        
        CandidatoAtividade::class => CandidatoAtividadePolicy::class,
        Documento::class => DocumentoPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}