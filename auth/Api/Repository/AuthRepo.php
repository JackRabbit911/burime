<?php

declare(strict_types=1);

namespace Auth\Api\Repository;

use Auth\Api\Model\ModelAuth;
use Auth\Api\Model\ModelRefreshToken;
use Firebase\JWT\JWT;

class AuthRepo
{
    private $config = [];

    public function __construct(
        private ModelAuth $modelAuth,
        private ModelRefreshToken $modelRefreshToken,
    )
    {
        $this->config = config('o2auth');
    }

    public function login(array $data)
    {
        $user = $this->modelAuth->auth($data['email'], $data['password']);
        return $this->forceLogin($user, $data['remember'] ?? false);
    }

    public function forceLogin(object $user, bool $remember = false): array
    {
        $refresh = $this->modelRefreshToken->initialSesssion($user->id, $remember);
        $bearer = $this->encodeJWT($user, $refresh);

        return [$user, $refresh, $bearer];
    }

    public function logout(?string $token): string|false
    {
        if (isset($token)) {
            return $this->modelRefreshToken->logout($token);
        }

        return false;
    }

    public function logoutGlobal()
    {

    }

    public function encodeJWT(object $user, $session_id): string
    {
        $iat = time();

        $payload = [
            'iss' => $this->config['iss'],
            'iat' => $iat,
            'exp' => $iat + $this->config['lifetime'],
            'sid' => bin2hex($session_id),
            'user' =>
            [
                'id' => $user->id,
                'name' => $user->name,
                'sex' => $user->sex,
                'dob' => $user->dob,
                'role' => $user->role,
            ]
        ];

        return JWT::encode($payload, $this->config['key'], $this->config['algo']);
    }

    public function find(int $user_id): object | null
    {
        return $this->modelAuth->find($user_id);
    }
}
