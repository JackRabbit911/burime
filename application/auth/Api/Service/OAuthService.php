<?php

declare(strict_types = 1);

namespace Auth\Api\Service;

use Auth\Model\ModelUser;
use Auth\Model\ModelUserToken;
use Auth\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;
use Throwable;

class OAuthService
{
    private array $config;

    public function __construct(
        private ModelUserToken $modelToken,
        private ModelUser $modelUser
    )
    {
        $this->config = config('api_o2auth');
    }

    public function logout($token)
    {
        $this->modelToken->delete($token);
    }

    public function getAccessToken(stdClass $user)
    {
        $now = time();
        $jwt = $this->encode($user, $now);

        return $jwt;
    }

    public function getRefreshToken($user_id)
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? uniqid();
        $user_agent = md5($user_agent);
        return $this->modelToken->create($user_agent, $user_id, 1);
    }

    public function authByJwt($token): ?User
    {
        $payload = $this->decode($token);

        if ($payload) {
            return User::fromArray((array) $payload->user);
        }

        return $payload?->user;
    }

    public function getUserByRefresh($token)
    {
        $user_id = $this->modelToken->read($token, 7200);
        return $this->modelUser->find($user_id);
    }

    public function getAccessByRefresh($token): ?string
    {
        $user_id = $this->modelToken->read($token, 7200);

        if ($user_id) {
            $user = $this->modelUser->find($user_id);
            $userDto = $this->getUserDto($user);
            return $this->getAccessToken($userDto);
        }

        return null;
    }

    public function getUserDto(User $user)
    {
        $userDto = new stdClass;
        $userDto->id = $user->id;
        $userDto->name = $user->name;

        return $userDto;
    }

    private function encode(stdClass $user, ?int $iat = null)
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
            ]
        ];

        return JWT::encode($payload, $this->config['key'], $this->config['algo']);
    }

    private function decode(string $jwt)
    {
        try {
            return JWT::decode($jwt, new Key($this->config['key'], $this->config['algo']));
        } catch (Throwable $e) {
            return null;
        }
    }
}
