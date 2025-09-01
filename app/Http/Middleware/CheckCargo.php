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
        if (!$user || !$user->empleado) {
            abort(403, 'Acceso denegado.');
        }
        $cargo = $user->empleado->cargoNombre();
        if (!in_array($cargo, $cargos)) {
            abort(403, 'Acceso denegado.');
        }
        return $next($request);
    }
}
