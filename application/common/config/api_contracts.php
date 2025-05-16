<?php

declare(strict_types=1);

return [
    'hosts' => [
        'http://localhost',
        'http://127.0.0.1:5500',
        'http://localhost:3000',
    ],
    'headers' => [
        'Authorization',
        'Content-Type',
        'Access-Control-Allow-Credentials',
        'Bearer',
        'X-Token',
    ],
    'methods' => [
        'get',
        'post',
        'patch',
        'delete',
    ],
    'max_age' => 120,
    'allow_credentials' => true,
];
