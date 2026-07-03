<?php

declare(strict_types=1);

namespace Adm\Repository;

use Adm\Model\ModelAuth;
use Adm\Model\ModelRefreshToken;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;
use Memcached;

class AuthRepo
{
    private array $config;

    public function __construct(
        private ModelAuth $modelAuth,
        private ModelRefreshToken $modelRefreshToken,
        private Memcached $cache
    ) {
        $this->config = config('api_o2auth');
    }

    public function login(): array
    {
        $user = $this->modelAuth->getUser();
        $refresh = $this->modelRefreshToken->initialSesssion($user->id);
        $bearer = $this->encodeJWT($user, md5($refresh));

        return [
            'user' => $user,
            'refresh' => $refresh,
            'bearer' => $bearer
        ];
    }

    public function logout(string $token): void
    {
        $session_id = $this->modelRefreshToken->logout($token);
        $this->cache->set('blacklist_sid:' . $session_id, 1, $this->config['lifetime']);
    }

    public function logoutGlobal(string $token): void
    {
        $session_ids = $this->modelRefreshToken->logoutGlobal($token);

        foreach ($session_ids as $sid) {
            $this->cache->set('blacklist_sid:' . $sid, 1, $this->config['lifetime']);
        }
    }

    public function rotate(string $token): array|false
    {
        $result = $this->modelRefreshToken->rotateToken($token);

        if (!$result) {
            return false;
        }

        return [
            'refresh' => $result['token'],
            'bearer' => $this->encodeJWT((object) $result['user'], md5($result['session_id'])),
        ];
    }

    public function ban($user_id): void
    {
        $this->modelRefreshToken->deleteByUser($user_id);
        $this->cache->set('blacklist_uid:' . $user_id, 1, $this->config['lifetime']);
    }

    private function encodeJWT($user, string $session_id): string
    {
        $iat = time();

        $payload = [
            'iss' => $this->config['iss'],
            'iat' => $iat,
            'exp' => $iat + $this->config['lifetime'],
            'sid' => $session_id,
            'user' =>
            [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ]
        ];

        return JWT::encode($payload, $this->config['key'], $this->config['algo']);
    }
}
