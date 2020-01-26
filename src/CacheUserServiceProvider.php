<?php

namespace Cesg\Auth;

use Illuminate\Auth\CreatesUserProviders;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class CacheUserServiceProvider extends ServiceProvider
{
    use CreatesUserProviders;

    public function register(): void
    {
        Auth::resolved(function ($auth) {
            $auth->provider('cache', function ($app, array $config) {
                $driver = $config['use'] ?? 'eloquent';

                return new CacheUserProvider(
                    $this->getUserProvider($driver, $config),
                    $app['cache'],
                    $config
                );
            });
        });
    }

    private function getUserProvider(string $driver, array $config): UserProvider
    {
        switch ($driver) {
            case 'database':
                return $this->createDatabaseProvider($config);
            case 'eloquent':
                return $this->createEloquentProvider($config);
            default:
                throw new InvalidArgumentException(
                    "Authentication user provider [{$driver}] is not defined."
                );
        }
    }
}
