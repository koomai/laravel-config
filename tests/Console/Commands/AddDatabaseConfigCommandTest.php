<?php

namespace Koomai\LaravelConfig\Tests\Console\Commands;

use Koomai\LaravelConfig\DatabaseConfig;
use Koomai\LaravelConfig\Tests\TestCase;
use Koomai\LaravelConfig\Providers\LaravelServiceProvider;

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

        $this->artisan('config:add '.implode(' ', $data));

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

        $this->artisan('config:add '.$data['name'].' '.$data['key'].' '.$data['value'][0].' '.$data['value'][1]);

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
            'value' => 'replacement',
        ];

        $this->artisan('config:add '.implode(' ', $data));

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
            'value' => 'replacement',
        ];

        $this->artisan('config:add '.implode(' ', $data));

        // Assert both key/values are set
        $this->assertDatabaseHas(
            $this->table,
            [
                'name' => $data['name'],
                'value' => json_encode(
                    [
                        'foo' => 'original',
                        $data['key'] => $data['value'],
                    ]
                ),
            ]
        );
    }
}
