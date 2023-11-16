<?php

namespace App\Providers;

use App\Services\EntityService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EntityService::class, function (Application $app) {
            $config = $app['config']['origami'];
            return new EntityService($config['username'], $config['password'], $config['apiUrl']);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
