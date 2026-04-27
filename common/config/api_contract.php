<?php

return [
    'hosts' => [
        'http://localhost',
        'http://ru.localhost',
        'http://en.localhost',
        'http://de.localhost',
        'http://localhost:3000',
        'http://localhost:5500',
        'http://localhost:5173',
        'http://ru.localhost:5173',
        'http://en.localhost:5173',
        'http://de.localhost:5173',
    ],
    'headers' => [
        'Authorization',
        'Content-Type',
        'X-Bearer',
        'X-Refresh',
    ],
    'methods' => [
        'get',
        'post',
        'delete',
        'patch',
        'put',
    ],
    'max_age' => 3600,
    'allow_credentials' => true,
];
