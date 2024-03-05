<?php

declare(strict_types=1);

namespace Swis\JsonApi\Client\Tests;

use Orchestra\Testbench\TestCase;
use Swis\JsonApi\Client\Providers\ServiceProvider;

abstract class AbstractTestCase extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        // Import default settings
        $defaultSettings = require __DIR__.'/../config/json-api-client.php';
        $app['config']->set('json-api-client', $defaultSettings);
    }

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }
}
