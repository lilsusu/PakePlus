<?php

return [
    'apps' => [
        [
            'key' => env('REVERB_APP_KEY', str()->uuid()->toString()),
            'secret' => env('REVERB_APP_SECRET', str()->uuid()->toString()),
            'id' => env('REVERB_APP_ID', str()->uuid()->toString()),
            'max_connections' => 1000,
            'allowed_origins' => ['*'],
            'ping_interval' => 60,
            'max_request_size' => 10000,
            'capabilities' => ['broadcasting', 'presence'],
        ],
    ],
    'default' => env('REVERB_DEFAULT', 'default'),
    'servers' => [
        'default' => [
            'host' => env('REVERB_HOST', '0.0.0.0'),
            'port' => (int) env('REVERB_PORT', 8080),
            'hostname' => env('REVERB_HOSTNAME'),
            'path' => env('REVERB_PATH', ''),
            'secure' => env('REVERB_SECURE', false),
            'access_token' => env('REVERB_ACCESS_TOKEN'),
            'verify_peer' => env('REVERB_VERIFY_PEER', true),
            'redis' => [
                'connection' => 'reverb',
            ],
        ],
    ],
];
