<?php

namespace Koomai\LaravelConfig\Providers;

use Illuminate\Support\ServiceProvider;
use Koomai\LaravelConfig\Console\Commands\AddDatabaseConfigCommand;
use Koomai\LaravelConfig\Console\Commands\DeleteDatabaseConfigCommand;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/database-config.php' => config_path('database-config.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                AddDatabaseConfigCommand::class,
                DeleteDatabaseConfigCommand::class,
            ]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/database-config.php', 'database-config');
    }
}
