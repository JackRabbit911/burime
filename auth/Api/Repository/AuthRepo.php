<?php

declare(strict_types=1);

namespace Auth\Api\Repository;

use Auth\Api\Model\ModelAuth;
use Auth\Api\Model\ModelRefreshToken;
use Firebase\JWT\JWT;
use HttpSoft\Response\EmptyResponse;

class AuthRepo
{
    private $config = [];

    public function __construct(
        private ModelAuth $modelAuth,
        private ModelRefreshToken $modelRefreshToken,
    ) {
        $this->config = config('o2auth');
    }

    public function auth(string $refresh)
    {
        $token_hash = $this->modelRefreshToken->hash($refresh);
        $user = $this->modelRefreshToken->getUserByToken($refresh);

        if (!$user) {
            return new EmptyResponse(401);
        }
        
        $bearer = $this->encodeJWT($user, $token_hash);

        return [
            'user' => $user,
            'bearer' => $bearer,
        ];
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

    public function logout(?string $token): void
    {
        if (isset($token)) {
            $this->modelRefreshToken->logout($token);
        }
    }

    public function logoutGlobal(?string $token)
    {
        if (isset($token)) {
            $this->modelRefreshToken->logoutGlobal($token);
        }
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
