<?php

return [
    'hosts' => [
        'http://localhost',
        'http://localhost:3000',
        'http://localhost:5500',
        'http://localhost:5173',
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
