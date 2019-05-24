<?php

namespace Koomai\LaravelConfig\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/factories');

        $this->artisan('migrate', ['--database' => 'testing']);
    }
}
