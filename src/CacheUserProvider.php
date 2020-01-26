<?php

namespace Cesg\Auth;

use Carbon\Carbon;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Arr;

class CacheUserProvider implements UserProvider
{
    protected $cache;
    protected $provider;
    protected $config;

    public function __construct(UserProvider $provider, CacheManager $cache, array $config)
    {
        $this->provider = $provider;
        $this->cache = $cache;
        $this->config = $config;
    }

    public function getProvider(): UserProvider
    {
        return $this->provider;
    }

    public function retrieveById($identifier)
    {
        return $this->cache->remember(
            "{$this->prefix()}:{$identifier}",
            $this->expire(),
            function () use ($identifier) {
                return $this->getProvider()->retrieveById($identifier);
            }
        );
    }

    public function retrieveByToken($identifier, $token)
    {
        return $this->cache->remember(
            "{$this->prefix()}:{$identifier}:{$token}",
            $this->expire(),
            function () use ($identifier, $token) {
                return $this->getProvider()->retrieveByToken($identifier, $token);
            }
        );
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param string $token
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $this->getProvider()->updateRememberToken($user, $token);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $this->getProvider()->validateCredentials($user, $credentials);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @return null|\Illuminate\Contracts\Auth\Authenticatable
     */
    public function retrieveByCredentials(array $credentials)
    {
        $key = "{$this->prefix()}:".\implode(':', Arr::except($credentials, 'password'));

        return $this->cache->remember($key, $this->expire(), function () use ($credentials) {
            return $this->getProvider()->retrieveByCredentials($credentials);
        });
    }

    private function prefix(): string
    {
        return $this->config['prefix'] ?? 'auth';
    }

    private function expire(): Carbon
    {
        return now()->addMinutes(
            $this->config['ttl'] ?? 120
        );
    }
}
