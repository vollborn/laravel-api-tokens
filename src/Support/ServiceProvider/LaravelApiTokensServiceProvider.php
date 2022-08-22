<?php

namespace Vollborn\LaravelApiTokens\Support\ServiceProvider;

use Illuminate\Support\ServiceProvider;

class LaravelApiTokensServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../Migrations');
    }
}
