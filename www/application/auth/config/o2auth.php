<?php

return [
    'name' => 'OAT',
    'algo' => 'HS256',
    'key' => env('OAUTH_KEY'),
    'lifetime' => env('OAUTH_LIFETIME'),
    'iss' => env('APP_NAME'),
    'cookie' => [
        'path'      => '/',
        'secure'    => false,
        'httponly'  => true,
    ]
];
