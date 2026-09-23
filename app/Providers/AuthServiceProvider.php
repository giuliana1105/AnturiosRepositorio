<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\TipoNota;
use App\Models\Bodega;
use App\Models\Producto;
use App\Models\Empleado;
use App\Models\User;
use App\Models\TransaccionProducto;
use App\Models\Venta;
use App\Policies\BodegaPolicy;
use App\Policies\TipoNotaPolicy;
use App\Policies\ProductoPolicy;
use App\Policies\EmpleadoPolicy;
use App\Policies\UserPolicy;
use App\Policies\TransaccionProductoPolicy;
use App\Policies\RolePolicy;
use App\Policies\VentaPolicy;
use App\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        TipoNota::class => TipoNotaPolicy::class,
        Bodega::class => BodegaPolicy::class,
        Producto::class => ProductoPolicy::class,
        Empleado::class => EmpleadoPolicy::class,
        User::class => UserPolicy::class,
        TransaccionProducto::class => TransaccionProductoPolicy::class,
        Role::class => RolePolicy::class,
        Venta::class => VentaPolicy::class,
    ];

    
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // El Administrador tiene acceso total sin restricciones
        Gate::before(function ($user, $ability) {
            if ($user->esAdministrador()) {
                return true;
            }
        });
    }
}
