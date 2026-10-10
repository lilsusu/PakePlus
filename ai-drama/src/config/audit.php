<?php

use OwenIt\Auditing\Resolvers;
use OwenIt\Auditing\Contracts\Audit;

return [
    'enabled' => env('AUDITING_ENABLED', true),
    'implementation' => \App\Models\AuditLog::class,
    'user' => [
        'resolver' => Resolvers\UserResolver::class,
        'columns' => [
            'user_id' => 'user_id',
            'user_type' => 'user_type',
        ],
    ],
    'resolver' => [
        'url' => Resolvers\UrlResolver::class,
        'ip_address' => Resolvers\IpAddressResolver::class,
        'user_agent' => Resolvers\UserAgentResolver::class,
    ],
    'events' => [
        'created', 'updated', 'deleted',
    ],
    'audit' => [
        'timestamp' => [
            'timezone' => 'Asia/Shanghai',
            'date_format' => 'Y-m-d H:i:s',
            'format' => 'Y-m-d H:i:s',
        ],
    ],
    'renderer' => \App\Audit\AuditRenderer::class,
    'queue' => false,
];
