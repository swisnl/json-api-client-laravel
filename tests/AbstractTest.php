<?php

namespace Swis\JsonApi\Client\Tests;

use Orchestra\Testbench\TestCase;
use Swis\JsonApi\Client\Providers\ServiceProvider;

abstract class AbstractTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        // Import default settings
        $defaultSettings = require __DIR__.'/../config/json-api.php';
        $app['config']->set('json-api', $defaultSettings);
    }

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }
}
