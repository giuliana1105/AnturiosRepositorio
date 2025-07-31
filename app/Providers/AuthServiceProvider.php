<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\TipoNota;
use App\Policies\BodegaPolicy;
use App\Policies\TipoNotaPolicy;
use App\Models\Bodega;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        TipoNota::class => TipoNotaPolicy::class, // ✅ Registra la política aquí
        Bodega::class=> BodegaPolicy::class,
    ];

    
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
