<?php
error_reporting(E_ALL & ~E_DEPRECATED);

// Redirigir la caché a /tmp porque Vercel es de solo lectura
$_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
$_ENV['APP_CONFIG_CACHE'] = '/tmp/config.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/routes.php';
$_ENV['APP_EVENTS_CACHE'] = '/tmp/events.php';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp';
putenv('VIEW_COMPILED_PATH=/tmp');
require __DIR__ . '/../public/index.php';
