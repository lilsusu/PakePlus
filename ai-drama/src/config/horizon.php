<?php

use Laravel\Horizon\Horizon;

// Horizon 配置 — AI 短剧平台队列监控
Horizon::auth(fn ($request) => true);

return [
    'use' => 'default',
    'prefix' => env('HORIZON_PREFIX', 'aidrama:'),
    'domain' => null,
    'path' => 'horizon',
    'notifications' => [
        'slack' => null,
        'email' => null,
        'twilio' => null,
        'webhook' => null,
    ],
    'night' => [
        'theme' => 'slack',
        'logo' => null,
    ],
    'defaults' => [
        'supervisor-1' => [
            'connection' => 'redis',
            'queue' => ['default', 'high', 'ai-task', 'payment', 'notification'],
            'balance' => 'auto',
            'autoScalingStrategy' => 'time',
            'maxProcesses' => 8,
            'maxTime' => 0,
            'maxJobs' => 0,
            'memory' => 256,
            'tries' => 3,
            'timeout' => 300,
            'nice' => 0,
            'backoff' => 5,
        ],
        'supervisor-name' => [
            'connection' => 'redis',
            'queue' => 'notification',
            'balance' => 'auto',
            'maxProcesses' => 4,
            'tries' => 5,
            'timeout' => 60,
        ],
    ],
];
