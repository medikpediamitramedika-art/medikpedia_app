<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Pastikan kode ini ada DI DALAM kurung kurawal boot
        $this->app->bind('path.public', function() {
            return base_path();
        });
    }
}