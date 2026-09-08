<?php

return [
    'max_active_sessions' => 4,
    'algo' => 'HS256',
    'key' => env('OAUTH_KEY'),
    'lifetime' => 300,
    'iss' => env('APP_NAME'),
    'refresh_lifetime' => 3600,//3600 * 2,
    'remember_lifetime' => 7200,//3600 * 24 * 30,
    'name' => 'OAT',
    'roottime' => 7200,
    'cookie' => [
        'path'      => '/',
        // 'domain' => '.localhost',
        'secure'    => ENV < DEVELOPMENT,
        'httponly'  => true,
        'samesite' => 'Lax',
    ],
    'exclude_urls' => [
        '/ava',
        '/api/adm/auth',
        // '/api/my/stat',
        // '/api/auth/login',
        // '/api/auth/logout',
        // '/api/console',
        // '/api/rating',
        // '/api/post',
    ],
];
