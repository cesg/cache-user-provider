# cache-user-provider
Laravel cache user provider

# Install
```sh
composer require cesg/cache-user-provider
```

# Configure
Configure the cache user provider.

auth.php

```php
'users' => [
    'driver' => 'cache',
    'model' => App\User::class,
    'use' => 'eloquent',
    'prefix' => 'auth',
    'ttl' => env('SESSION_LIFETIME', 120)
],
```


