<?php

namespace Koomai\LaravelConfig\Tests\Console\Commands;

use Koomai\LaravelConfig\DatabaseConfig;
use Koomai\LaravelConfig\Providers\LaravelServiceProvider;
use Koomai\LaravelConfig\Tests\TestCase;

class AddDatabaseConfigCommandTest extends TestCase
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
    public function shouldAddANewConfigKeyAndValue()
    {
        $data = [
            'name' => 'test',
            'key' => 'foo',
            'value' => 'bar',
        ];

        $this->artisan(
            'config:add',
            [
                'name' => $data['name'],
                'key' => $data['key'],
                'value' => [$data['value']],
            ]
        );

        $this->assertDatabaseHas(
            $this->table,
            [
                'name' => $data['name'],
                'value' => json_encode([$data['key'] => $data['value']]),
            ]
        );
    }

    /**
     * @test
     */
    public function shouldAddANewConfigKeyAndArrayValue()
    {
        $data = [
            'name' => 'test',
            'key' => 'foo',
            'value' => ['bar', 'baz'],
        ];

        $this->artisan(
            'config:add',
            [
                'name' => $data['name'],
                'key' => $data['key'],
                'value' => $data['value'],
            ]
        );

        $this->assertDatabaseHas(
            $this->table,
            [
                'name' => $data['name'],
                'value' => json_encode([$data['key'] => $data['value']]),
            ]
        );
    }

    /**
     * @test
     */
    public function shouldUpdateValueIfKeyAlreadyExists()
    {
        $originalConfig = [
            'name' => 'test',
            'value' => ['foo' => 'original'],
        ];

        factory(DatabaseConfig::class)->create($originalConfig);

        $this->assertDatabaseHas(
            $this->table,
            [
                'name' => $originalConfig['name'],
                'value' => json_encode($originalConfig['value']),
            ]
        );

        // Update with new config value for foo
        $data = [
            'name' => 'test',
            'key' => 'foo',
            'value' => 'new value',
        ];

        $this->artisan(
            'config:add',
            [
                'name' => $data['name'],
                'key' => $data['key'],
                'value' => [$data['value']],
            ]
        );

        // Assert original value does not exist
        $this->assertDatabaseMissing(
            $this->table,
            [
                'name' => $data['name'],
                'value' => json_encode([$data['key'] => 'original']),
            ]
        );

        // Assert new value is set
        $this->assertDatabaseHas(
            $this->table,
            [
                'name' => $data['name'],
                'value' => json_encode([$data['key'] => $data['value']]),
            ]
        );
    }

    /**
     * @test
     */
    public function shouldAppendNewKeyValueIfNameExists()
    {
        $originalConfig = [
            'name' => 'test',
            'value' => ['foo' => 'original'],
        ];

        factory(DatabaseConfig::class)->create($originalConfig);

        $this->assertDatabaseHas(
            $this->table,
            [
                'name' => $originalConfig['name'],
                'value' => json_encode($originalConfig['value']),
            ]
        );

        // Update with new config value for foo
        $data = [
            'name' => 'test',
            'key' => 'bar',
            'value' => 'new value',
        ];

        $this->artisan(
            'config:add',
            [
                'name' => $data['name'],
                'key' => $data['key'],
                'value' => [$data['value']],
            ]
        );

        // Assert both key/values are set
        $this->assertDatabaseHas(
            $this->table,
            [
                'name' => $data['name'],
                'value' => json_encode(
                    [
                        'foo' => 'original',
                        $data['key'] => $data['value']
                    ]
                ),
            ]
        );
    }
}
