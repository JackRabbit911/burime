<?php

return [
    'algo' => 'HS256',
    'key' => env('OAUTH_KEY'),
    'lifetime' => 300,
    'iss' => env('APP_NAME'),
    'refresh_lifetime' => 3600 * 2,
    'refresh_update' => 3600,
    'exclude_urls' => [
        '/auth/login',
        '/auth/logout',
        '/console'
    ]
];
