<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Deteksi environment: hosting (public_html) vs lokal (public/)
|--------------------------------------------------------------------------
| Di hosting: semua file Laravel ada di public_html/ (satu level)
| Di lokal:   file Laravel ada di folder parent dari public/
*/
$isHosting = file_exists(__DIR__ . '/vendor/autoload.php');
$basePath  = $isHosting ? __DIR__ : __DIR__ . '/..';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $basePath . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $basePath . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once $basePath . '/bootstrap/app.php')
    ->handleRequest(Request::capture());
