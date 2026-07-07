<?php

declare(strict_types=1);

namespace App\Api\Auth\Repository;

use App\Api\Auth\Model\ModelAuth;
use Auth\Model\ModelRefreshToken;
use Auth\Model\ModelRememberToken;
use Firebase\JWT\JWT;

class AuthRepo
{
    private $config = [];

    public function __construct(
        private ModelAuth $modelAuth,
        private ModelRefreshToken $modelRefreshToken,
        private ModelRememberToken $modelRemember,
    )
    {
        $this->config = config('o2auth');
    }

    public function login(array $data)
    {
        $user = $this->modelAuth->auth($data['email'], $data['password']);
        [$refresh, $bearer, $remember] = $this->forceLogin($user, $data['remember'] ?? false);

        // $refresh = $this->modelRefreshToken->initialSesssion($user->id);
        // $bearer = $this->encodeJWT($user, md5($refresh));

        // if ($data['remember']) {
        //     $remember = $this->modelRemember->create($user->id);
        // } else {
        //     $remember = null;
        // }

        return [$refresh, $bearer, $remember];
    }

    public function forceLogin(object $user, bool $remember = false): array
    {
        $refresh = $this->modelRefreshToken->initialSesssion($user->id);
        $bearer = $this->encodeJWT($user, md5($refresh));

        if ($remember) {
            $remember = $this->modelRemember->create($user->id);
        } else {
            $remember = null;
        }

        return [$refresh, $bearer, $remember];
    }

    public function logout(array $cookie): string|false
    {
        if (isset($cookie['RMT'])) {
            $this->modelRemember->delete($cookie['RMT']);
        }

        if (isset($cookie['UAT'])) {
            return $this->modelRefreshToken->logout($cookie['UAT']);
        }

        return false;
    }

    public function logoutGlobal()
    {

    }

    public function encodeJWT(object $user, string $session_id): string
    {
        // dd($user);
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
                'sex' => $user->sex,
                'dob' => $user->dob,
            ]
        ];

        return JWT::encode($payload, $this->config['key'], $this->config['algo']);
    }
}
