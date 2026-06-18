<?php

declare(strict_types=1);

namespace Adm\Service;

use Firebase\JWT\JWT;

class Tokens
{
    private array $config;

    public function __construct()
    {
        $this->config = config('api_o2auth');
    }

    public function generateRefreshToken($user_id): array
    {
        $data['user_agent'] = md5($_SERVER['HTTP_USER_AGENT']);
        $salt = $user_agent ?? uniqid();
        $data['token'] = sha1($salt . time() . bin2hex(random_bytes(16)));
        $data['user_id'] = $user_id;

        return $data;
    }

    public function encodeJWT($user, ?int $iat = null): string
    {
        $iat = $iat ?? time();

        $payload = [
            'iss' => $this->config['iss'],
            'iat' => $iat,
            'exp' => $iat + $this->config['lifetime'],
            'user' =>
            [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ]
        ];

        return JWT::encode($payload, $this->config['key'], $this->config['algo']);
    }

    public function decodeJWT(string $jwt): object|bool
    {
        try {
            return JWT::decode($jwt, new Key($this->config['key'], $this->config['algo']));
        } catch (ExpiredException $e) {
            return true;
        } catch (UnexpectedValueException $e) {
            return false;
        }
    }
}
