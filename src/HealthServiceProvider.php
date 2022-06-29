<?php

namespace HvacHealth;

use HvacHealth\Commands\RunHealthMonitorsCommand;
use Illuminate\Support\ServiceProvider;

class HealthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/hvac-health.php' => config_path('hvac-health.php')
        ], 'config');

        $this->commands([
            RunHealthMonitorsCommand::class
        ]);
    }

    public function register()
    {
        $this->app->singleton(Health::class, fn () => new Health());
        $this->app->bind('health', Health::class);
    }
}
