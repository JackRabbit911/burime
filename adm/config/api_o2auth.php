<?php

return [
    'algo' => 'HS256',
    'key' => env('OAUTH_KEY'),
    'lifetime' => 30,
    'iss' => env('APP_NAME'),
    'refresh_lifetime' => 3600 * 2,
    'refresh_update' => 120,
    'exclude_urls' => [
        '/api/auth/login',
        '/api/auth/logout',
        '/api/console',
        '/api/rating',
        '/api/post',
    ],
];
