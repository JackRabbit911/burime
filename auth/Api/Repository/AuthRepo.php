<?php

declare(strict_types=1);

namespace Auth\Api\Repository;

use Auth\Api\Model\ModelAuth;
use Auth\Api\Model\ModelRefreshToken;
use Firebase\JWT\JWT;
use HttpSoft\Response\EmptyResponse;
use Memcached;

class AuthRepo
{
    private $config = [];

    public function __construct(
        private ModelAuth $modelAuth,
        private ModelRefreshToken $modelRefreshToken,
        private Memcached $cache,
    ) {
        $this->config = config('o2auth');
    }

    public function auth(string $refresh)
    {
        $user = $this->modelRefreshToken->getUserByToken($refresh);

        return (($user)) ?: new EmptyResponse(401);

        if (!$user) {
            return new EmptyResponse(401);
        }
        
        $bearer = $this->encodeJWT($user);

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
        $bearer = $this->encodeJWT($user);

        return [$user, $refresh, $bearer];
    }

    public function rotate(string $token): object|false
    {
        $token_hash = $this->modelRefreshToken->hash($token);
        $user = $this->cache->get('token:' . $token);

        if ($user) {
            $result = (object) [
                'user' => $user,
                'token_hash' => $token_hash,
            ];
        } else {
            $result = $this->modelRefreshToken->rotateToken($token);
        }

        if (!$result) {
            return false;
        }

        $result->bearer = $this->encodeJWT($result->user);

        return $result;
    }

    public function logout(?string $token): void
    {
        if (isset($token)) {
            $this->modelRefreshToken->logout($token);
        }
    }

    public function logoutGlobal(?string $token): void
    {
        if (isset($token)) {
            $this->modelRefreshToken->logoutGlobal($token);
        }
    }

    public function encodeJWT(object $user): string
    {
        $iat = time();

        $payload = [
            'iss' => $this->config['iss'],
            'iat' => $iat,
            'exp' => $iat + $this->config['lifetime'],
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
