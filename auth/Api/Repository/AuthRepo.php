<?php

declare(strict_types=1);

namespace Auth\Api\Repository;

use Auth\Api\Model\ModelAuth;
use Auth\Api\Model\ModelRefreshToken;
use Sys\CSRF\Driver\Db as CSRF;
use Firebase\JWT\JWT;
use HttpSoft\Response\EmptyResponse;
use Memcached;

class AuthRepo
{
    private $config = [];

    public function __construct(
        private ModelAuth $modelAuth,
        private ModelRefreshToken $modelRefreshToken,
        private CSRF $csrf,
        private Memcached $cache,
    ) {
        $this->config = config('o2auth');
    }

    public function auth(string $refresh)
    {
        $user = $this->modelRefreshToken->getUserByToken($refresh);

        if (!$user) {
            return new EmptyResponse(401);
        }
        
        return $this->encodeJWT($user);
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

    public function logout(string $token, ?string $csrf): void
    {
        $user_id = $this->modelRefreshToken->logout($token);

        if (isset($csrf)) {
            $this->csrf->delete($csrf);
        } elseif (isset($user_id)) {
            $this->csrf->deleteByUserAgent($user_id);
        }
    }

    public function logoutGlobal(?string $token): void
    {
        if (isset($token)) {
            $user_id = $this->modelRefreshToken->logoutGlobal($token);

            if ($user_id) {
                $this->csrf->deleteByUser($user_id);
            }
        }
    }

    public function logoutOthers(string $token): void
    {
        $this->modelRefreshToken->logoutOthers($token);
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
