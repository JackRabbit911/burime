<?php

declare(strict_types=1);

namespace App\Api\Auth\Service;

use Firebase\JWT\JWT;

class OAuth
{
    private array $config;

    public function __construct()
    {
        $this->config = config('o2auth');
    }

    public function login(object $user)
    {
        $now = time();
        $jwt = $this->encode($user, $now);

        $options = $this->config['cookie'];
        $options['expires'] = time() + $this->config['lifetime'];

        setcookie($this->config['name'], $jwt, $options);
    }

    private function encode(object $user, ?int $iat = null)
    {
        if (!$iat) {
            $iat = time();
        }

        $payload = [
            'iss' => $this->config['iss'],
            'iat' => $iat,
            'exp' => $iat + $this->config['lifetime'],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'dob' => $user->dob,
                'sex' => $user->sex,
            ]
        ];

        return JWT::encode($payload, $this->config['key'], $this->config['algo']);
    }
}
