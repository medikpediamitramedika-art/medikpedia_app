<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Di hosting public_html = root project, path.public tetap base_path()
        // Di lokal, path.public = base_path('public')
        $this->app->bind('path.public', function () {
            // Cek apakah kita di hosting (vendor ada di root yang sama dengan index.php)
            if (file_exists(base_path('vendor/autoload.php')) &&
                file_exists(base_path('index.php'))) {
                return base_path(); // hosting: public_html adalah root
            }
            return base_path('public'); // lokal: subfolder public/
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
