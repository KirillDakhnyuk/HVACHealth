<?php

namespace HvacHealth;

use HvacHealth\Commands\RunHealthMonitorsCommand;
use HvacHealth\Commands\ScheduleHeartbeatMonitorCommand;
use Illuminate\Support\ServiceProvider;

class HealthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $langPath = '/../lang/vendor/hvac-health';
        
        $langPath = (function_exists('lang_path'))
            ? lang_path($langPath)
            : resource_path('lang/' . $langPath);
        
        $this->publishes([
            __DIR__.'/../config/hvac-health.php' => config_path('hvac-health.php'),
        ], 'hvac-health');
        
        $this->publishes([
            __DIR__.('/../lang') => $langPath,
        ], 'hvac-health-translations');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'hvac-health');

        $this->commands([
            RunHealthMonitorsCommand::class,
            ScheduleHeartbeatMonitorCommand::class,
        ]);
    }

    public function register()
    {
        $this->app->singleton(Health::class, function () {
            return new Health();
        });
        $this->app->bind('health', Health::class);
    }
}
