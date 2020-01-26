<?php

namespace Cesg\Auth\Tests;

use Cesg\Auth\CacheUserServiceProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

/**
 * @internal
 * @coversNothing
 */
class TestCase extends TestbenchTestCase
{
    protected function getPackageProviders($app)
    {
        return [CacheUserServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users', [
            'driver' => 'cache',
            'model' => User::class,
        ]);
    }
}

class User extends \Illuminate\Foundation\Auth\User
{
    protected $guarded = [];
}
