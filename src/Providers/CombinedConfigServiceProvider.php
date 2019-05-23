<?php

namespace Koomai\LaravelConfig\Providers;

use Koomai\LaravelConfig\DatabaseConfig;
use Illuminate\Support\ServiceProvider;
use Illuminate\Config\Repository;

class CombinedConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // If app config is already cached, simply use the cached config key/values.
        // You will have to clear config cache to merge new database config
        // values with application config and cache them again.
        $items = [];
        if (file_exists($cached = $this->app->getCachedConfigPath())) {
            $items = require $cached;

            $loadedFromCache = true;
        }

        if (!isset($loadedFromCache)) {
            // Load database config
            $databaseConfig = $this->app['cache']->rememberForever(
                $this->app['config']->get('database-config.cache_key'),
                function () {
                    return DatabaseConfig::all()
                                         ->mapWithKeys(function ($config) {
                                             return [$config->name => $config->value];
                                         })
                                         ->all();
                }
            );

            $config = $this->app['config']->all();
            $items = array_merge_recursive_distinct($config, $databaseConfig);
        }

        $repository = new Repository($items);

        // Register custom instance in container
        $this->app->instance('config', $repository);
    }
}
