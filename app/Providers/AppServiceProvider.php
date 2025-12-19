<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar longitud de strings por defecto para MySQL
        Schema::defaultStringLength(191);
        
        // Asegurar que la conexión use UTF-8
        if (config('database.default') === 'mysql') {
            \DB::statement("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
        }
    }
}
