<?php

namespace Cesg\Auth\Tests;

use Cesg\Auth\CacheUserProvider;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Cache;

/**
 * @internal
 * @coversNothing
 */
class ProviderDriverTest extends TestCase
{
    /** @test */
    public function itConfigureAsUserProviderDriver()
    {
        $provider = \config('auth.providers.users.driver');
        $this->assertSame('cache', $provider);
        $auth = \app('auth');
        $provider = $auth->getProvider();
        $this->assertSame(CacheUserProvider::class, \get_class($provider));
    }

    /** @test */
    public function itUseCacheToStore()
    {
        $user = new User(['id' => 1]);

        /** @var \Illuminate\Contracts\Auth\UserProvider $userProvider */
        $userProvider = $this->partialMock(UserProvider::class, function ($mock) use ($user) {
            $mock->shouldReceive('retrieveById')->andReturn($user);
        });
        $driver = new CacheUserProvider(
            $userProvider,
            app('cache'),
            ['prefix' => 'auth', 'ttl' => 120]
        );
        $auth = $driver->retrieveById(1);
        $this->assertNotNull($auth);
        $this->assertTrue(
            Cache::has("auth:{$user->id}")
        );
    }
}
