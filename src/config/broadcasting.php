<?php

return [
    'default' => env('BROADCAST_CONNECTION', 'reverb'),

    'connections' => [
        'reverb' => [
            'driver' => 'reverb',
            'key' => 'my-app-key',
            'secret' => 'my-app-secret',
            'app_id' => 'my-app-id',
            'options' => [
                'host' => 'reverb', // <-- Hardcode ke service docker reverb
                'port' => 6001,     // <-- Hardcode ke port 6001
                'scheme' => 'http',
                'useTLS' => false,
            ],
            'client_options' => [],
        ],

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'host' => env('PUSHER_HOST') ?: 'api-'.env('PUSHER_APP_CLUSTER', 'mt1').'.pusher.com',
                'port' => 443,
                'scheme' => 'https',
                'encrypted' => true,
                'useTLS' => true,
            ],
        ],

        'ably' => ['driver' => 'ably', 'key' => env('ABLY_KEY')],
        'mercure' => ['driver' => 'mercure'],
        'log' => ['driver' => 'log'],
        'null' => ['driver' => 'null'],
    ],
];