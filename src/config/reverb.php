<?php

return [
    'default' => 'reverb',

    'servers' => [
        'reverb' => [
            'host' => '0.0.0.0',
            'port' => 6001,
            'path' => '',
            'hostname' => 'reverb',
            'options' => [
                'tls' => [],
            ],
            'max_request_size' => 10_000,
            'scaling' => [
                'enabled' => false,
                'channel' => 'reverb',
                'server' => [
                    'url' => env('REDIS_URL'),
                    'host' => 'redis',
                    'port' => '6379',
                    'username' => null,
                    'password' => null,
                    'database' => '0',
                    'timeout' => 60,
                ],
            ],
            'pulse_ingest_interval' => 15,
            'telescope_ingest_interval' => 15,
        ],
    ],

    'apps' => [
        'provider' => 'config',
        'apps' => [
            [
                'key' => 'my-app-key',
                'secret' => 'my-app-secret',
                'app_id' => 'my-app-id',
                'options' => [
                    'host' => 'reverb', // <-- Hardcode ke service docker reverb
                    'port' => 6001,     // <-- Hardcode ke port 6001
                    'scheme' => 'http',
                    'useTLS' => false,
                ],
                'allowed_origins' => ['*'],
                'ping_interval' => 60,
                'activity_timeout' => 30,
                'max_connections' => null,
                'max_message_size' => 10_000,
                'accept_client_events_from' => 'members',
                'rate_limiting' => [
                    'enabled' => false,
                    'max_attempts' => 60,
                    'decay_seconds' => 60,
                    'terminate_on_limit' => false,
                ],
            ],
        ],
    ],
];