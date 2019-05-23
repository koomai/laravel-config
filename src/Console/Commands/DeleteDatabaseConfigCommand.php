<?php

namespace Koomai\LaravelConfig\Console\Commands;

use Koomai\LaravelConfig\DatabaseConfig;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class DeleteDatabaseConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:delete 
                            {name : The config namespace, e.g. database, dashboard} 
                            {key : Attribute name – you can use Laravel "dot" notation for nested attributes}
                            {--reset-cache : Resets config cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a configuration key/value pair from the database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $key = $this->argument('key');
        $config = DatabaseConfig::find($name);

        if (!$config) {
            $this->error("No configuration for [{$name}] found");

            return 1;
        }

        if ($key === '') {
            $config->delete();
            $this->info("All config for [{$name}] deleted successfully.");
            $this->invalidateCache();

            return 0;
        }

        $newConfig = Arr::except($config->value, $key);
        $config->value = $newConfig;
        $config->save();

        $this->invalidateCache();

        $this->info("Config [{$name}.{$key}] deleted successfully.");

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

