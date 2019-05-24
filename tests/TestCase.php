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

        // Call run() method on new versions of Laravel
        $migrate = $this->artisan('migrate', ['--database' => 'testing']);
        if (!is_int($migrate)) {
            $migrate->run();
        }
    }
}
