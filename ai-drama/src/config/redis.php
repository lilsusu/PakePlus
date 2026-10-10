<?php

return [
    'client' => env('REDIS_CLIENT', 'predis'),
    'options' => [
        'cluster' => env('REDIS_CLUSTER', 'redis'),
        'prefix' => env('REDIS_PREFIX', 'aidrama:'),
        'scheme' => env('REDIS_SCHEME', 'tcp'),
        'persistent' => env('REDIS_PERSISTENT', false),
    ],
    'default' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', 'redis'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', 6379),
        'database' => env('REDIS_DB', 0),
    ],
    'cache' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', 'redis'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', 6379),
        'database' => env('REDIS_CACHE_DB', 1),
    ],
    'queue' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', 'redis'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', 6379),
        'database' => env('REDIS_QUEUE_DB', 2),
    ],
    'horizon' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', 'redis'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', 6379),
        'database' => env('REDIS_HORIZON_DB', 3),
    ],
    'reverb' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', 'redis'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', 6379),
        'database' => env('REDIS_REVERB_DB', 4),
    ],
];
