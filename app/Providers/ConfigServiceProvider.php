<?php

namespace App\Providers;

use App\Models\KonfigurasiSekolah;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $config = KonfigurasiSekolah::getConfig();
        
        View::composer('*', function ($view) use ($config) {
            $view->with('config', $config);
        });
    }
}
