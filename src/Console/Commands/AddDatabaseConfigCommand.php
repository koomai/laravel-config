<?php

namespace Koomai\LaravelConfig\Console\Commands;

use Koomai\LaravelConfig\DatabaseConfig;
use Illuminate\Console\Command;

class AddDatabaseConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:add 
                            {name : The config namespace, e.g. database, dashboard} 
                            {key : Attribute name – you can use Laravel "dot" notation for nested attributes}
                            {value* : Attribute value - pass multiple values separated by space for an array}
                            {--reset-cache : Resets config cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a configuration key/value pair in the database';

    /**
     * Allows arrays to be set as config values. But if there is only
     * one item in the array, it returns the value as a primitive.
     *
     * @param $valueAsArray
     *
     * @return mixed
     */
    private static function parseValue(array $valueAsArray)
    {
        return (count($valueAsArray) > 1)
            ? $valueAsArray
            : reset($valueAsArray);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $key = $this->argument('key');
        $value = self::parseValue($this->argument('value'));

        $config = DatabaseConfig::find($name);

        if (!$config) {
            $attributes = [];
            $newConfig = new DatabaseConfig;
            $newConfig->name = $name;
            $newConfig->value = data_set($attributes, $key, $value);
            $newConfig->save();

            $this->info("New config for {$name} saved successfully.");
        } else {
            $currentConfig = $config->value;
            data_set($currentConfig, $key, $value);

            $config->value = $currentConfig;
            $config->save();

            $this->info("Config for {$name} updated successfully.");
        }

        $this->invalidateCache();

        return 0;
    }

    /**
     * Flushes cache for database config
     * If --reset-cache option is passed, it will also re-cache app config
     *
     * @return void
     */
    private function invalidateCache(): void
    {
        app('cache')->forget(config('database-config.cache_key'));

        if ($this->option('reset-cache')) {
            $this->call('config:cache');
        }
    }
}

