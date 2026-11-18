<?php

use Illuminate\Http\Request;

// Aumentar el tiempo límite a 120s para tolerar latencias y arranques en frío de la base de datos remota (Supabase)
ini_set('max_execution_time', '120');

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
