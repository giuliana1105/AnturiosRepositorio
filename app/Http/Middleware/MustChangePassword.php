<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MustChangePassword
{
    /**
     * Si el usuario tiene must_change_password = true, lo redirige
     * obligatoriamente al formulario de cambio de contraseña.
     * Solo permite acceder a las rutas de cambio de contraseña y logout.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->must_change_password) {
            // Permitir solo las rutas de cambio de contraseña y logout
            $allowedRoutes = [
                'password.change.form',
                'password.change',
                'logout',
            ];

            $currentRoute = $request->route()?->getName();

            if (!in_array($currentRoute, $allowedRoutes)) {
                return redirect()->route('password.change.form')
                    ->with('warning', 'Debes cambiar tu contraseña antes de acceder al sistema.');
            }
        }

        return $next($request);
    }
}
