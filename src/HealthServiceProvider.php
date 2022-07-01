<?php

namespace HvacHealth;

use HvacHealth\Commands\RunHealthMonitorsCommand;
use HvacHealth\Commands\ScheduleHeartbeatMonitorCommand;
use Illuminate\Support\ServiceProvider;

class HealthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/hvac-health.php' => config_path('hvac-health.php')
        ], 'hvac-health');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'hvac-health');

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/hvac-health'),
        ]);

        $this->commands([
            RunHealthMonitorsCommand::class,
            ScheduleHeartbeatMonitorCommand::class,
        ]);
    }

    public function register()
    {
        $this->app->singleton(Health::class, fn () => new Health());
        $this->app->bind('health', Health::class);
    }
}
