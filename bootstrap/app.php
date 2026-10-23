<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Confiar en los proxies de Fly.io para generar URLs HTTPS correctamente
        $middleware->trustProxies(at: '*');

        $middleware->appendToGroup('auth', [
            \App\Http\Middleware\MustChangePassword::class,
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Manejar error 419 (Token CSRF expirado) redirigiendo al login
        $exceptions->renderable(function (TokenMismatchException $e, $request) {
            return redirect()->route('login')
                ->with('error', 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
        });

        // Manejar errores de conexión con la base de datos (pérdida de internet / Supabase caído)
        $exceptions->render(function (\PDOException $e, $request) {
            return response()->view('errors.500', [], 500);
        });

        $exceptions->render(function (\Illuminate\Database\QueryException $e, $request) {
            if ($e->getPrevious() instanceof \PDOException || 
                str_contains($e->getMessage(), 'could not translate host name') || 
                str_contains($e->getMessage(), 'Connection refused') || 
                str_contains($e->getMessage(), 'Connection timed out') ||
                !config('app.debug')) {
                return response()->view('errors.500', [], 500);
            }
        });
    })->create();

