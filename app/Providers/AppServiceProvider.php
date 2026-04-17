<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Override public path agar mengarah ke root project
        // Di hosting: public_html = root project (bukan subfolder public/)
        $this->app->bind('path.public', function () {
            return base_path();
        });
    }

    public function boot(): void
    {
        // Vite build directory langsung di root public_html/build
        Vite::useBuildDirectory('build');

        // Pastikan folder storage framework selalu ada
        $dirs = [
            storage_path('framework/views'),
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('app/public'),
            storage_path('app/public/medicines'),
            storage_path('app/public/activities'),
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }
        }
    }
}
