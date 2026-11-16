<?php

declare(strict_types=1);

return [
    // Where the app under test is served. Falls back to APP_URL, then a sane
    // local default. You start the server; Tetryon drives the browser.
    'base_url' => env('TETRYON_BASE_URL', env('APP_URL', 'http://127.0.0.1:8000')),

    'headless' => env('TETRYON_HEADLESS', true),

    // Explicit path to the Firefox binary; null = auto-locate.
    'firefox_binary' => env('TETRYON_FIREFOX_BINARY'),

    'timeout' => [
        'default' => 5000,
        'navigation' => 15000,
        'assertion' => 5000,
    ],

    'viewport' => [
        'width' => 1280,
        'height' => 720,
    ],

    'artifacts' => [
        'path' => env('TETRYON_ARTIFACTS_PATH', 'tests/Browser/Artifacts'),
    ],

    'selectors' => [
        'test_attributes' => ['data-testid', 'data-test', 'data-cy'],
    ],

    // Parallel runs: one server per worker (TETRYON_MANAGED_SERVER=true with
    // `php artisan test --parallel`). Same shape as bin/browser-serve.sh, on the
    // worker's own database, which Laravel names "<name>_test_<token>".
    'server' => [
        'managed' => env('TETRYON_MANAGED_SERVER', false),
        'command' => 'php -d opcache.enable=0 -S {host}:{port} ../vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php',
        'host' => '127.0.0.1',
        'base_port' => 8100,
        'ready_path' => '/up',
        'ready_timeout' => 15000,
        'env' => [
            'APP_ENV' => 'local',
            'APP_URL' => 'http://{host}:{port}',
            'SESSION_DRIVER' => 'file',
            'CACHE_STORE' => 'file',
            'QUEUE_CONNECTION' => 'sync',
            'DB_CONNECTION' => 'sqlite',
            'DB_DATABASE' => database_path('tetryon.sqlite').'_test_{token}',
        ],
        'working_directory' => public_path(),
    ],
];
