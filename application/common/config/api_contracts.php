<?php declare(strict_types=1);

return [
    '/api/test' => [
        'hosts' => [
            'http://127.0.0.1:5500',
        ],
        'headers' => [
            'Authorization',
            'Content-Type',
        ],
        'methods' => [
            'get', 'post', 'patch',
        ],
        'max_age' => 10,
        'allow_credentials' => true,
    ]
];
