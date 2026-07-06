<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCargo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$cargos): Response
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Acceso denegado.');
        }
        // El Administrador tiene acceso total sin restricciones
        if ($user->hasRole('Administrador') || $user->hasRole('super-admin') || $user->cargoNombre() === 'Administrador' || $user->email === 'admin@gmail.com') {
            return $next($request);
        }
        if (!$user->empleado) {
            abort(403, 'Acceso denegado.');
        }
        $cargo = $user->empleado->cargoNombre();
        if (!in_array($cargo, $cargos)) {
            abort(403, 'Acceso denegado.');
        }
        return $next($request);
    }
}
