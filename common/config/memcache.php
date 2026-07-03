<?php

declare(strict_types=1);

return [
    'host' => env('MEMCACHE_HOST'),
    'port' => 11211,
    'options' => [
        Memcached::OPT_BINARY_PROTOCOL  => true,
        Memcached::OPT_NO_BLOCK         => true,
        Memcached::OPT_TCP_NODELAY      => true,
        Memcached::OPT_COMPRESSION      => true,
        Memcached::OPT_CONNECT_TIMEOUT  => 1000,
        Memcached::OPT_POLL_TIMEOUT     => 1000,
        Memcached::OPT_PREFIX_KEY       => env('MEMCACHE_PREFIX'),
    ],
];
