<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
    Vite::useBuildDirectory('build');
        // Pastikan kode ini ada DI DALAM kurung kurawal boot
        $this->app->bind('path.public', function() {
            return base_path();
        });
    }
}