<?php

namespace Koomai\LaravelConfig\Tests\Console\Commands;

use Koomai\LaravelConfig\DatabaseConfig;
use Koomai\LaravelConfig\Providers\LaravelServiceProvider;
use Koomai\LaravelConfig\Tests\TestCase;

class DeleteDatabaseConfigCommandTest extends TestCase
{
    /** @var string */
    public $table;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->table = config('database-config.table');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
//         $app['config']->set('scheduler.environments', ['production']);
    }

    protected function getPackageProviders($app)
    {
        return [LaravelServiceProvider::class];
    }

    /**
     * @test
     */
    public function shouldDeleteKeyValuePair()
    {
        $originalConfig = [
            'name' => 'test',
            'value' => [
                'foo' => 'original',
                'bar' => 'new value',
            ],
        ];

        factory(DatabaseConfig::class)->create($originalConfig);

        $this->assertDatabaseHas(
            $this->table,
            [
                'name' => $originalConfig['name'],
                'value' => json_encode($originalConfig['value']),
            ]
        );

        // Delete key bar
        $this->artisan(
            'config:delete',
            [
                'name' => $originalConfig['name'],
                'key' => 'bar',
            ]
        );

        // Original config should not exist
        $this->assertDatabaseMissing(
            $this->table,
            [
                'name' => $originalConfig['name'],
                'value' => json_encode($originalConfig['value']),
            ]
        );

        // New array with deleted key/value pair
        $this->assertDatabaseHas(
            $this->table,
            [
                'name' => $originalConfig['name'],
                'value' => json_encode(
                    [
                        'foo' => 'original',
                    ]
                ),
            ]
        );
    }

    /**
     * @test
     */
    public function shouldDeleteNameWhenKeyIsEmptyString()
    {
        $originalConfig = [
            'name' => 'test',
            'value' => [
                'foo' => 'original',
                'bar' => 'new value',
            ],
        ];

        factory(DatabaseConfig::class)->create($originalConfig);

        $this->assertDatabaseHas(
            $this->table,
            [
                'name' => $originalConfig['name'],
                'value' => json_encode($originalConfig['value']),
            ]
        );

        // Delete name by passing empty string as key
        $this->artisan(
            'config:delete',
            [
                'name' => $originalConfig['name'],
                'key' => '',
            ]
        );

        // Original config should not exist
        $this->assertDatabaseMissing(
            $this->table,
            [
                'name' => $originalConfig['name'],
            ]
        );
    }
}
