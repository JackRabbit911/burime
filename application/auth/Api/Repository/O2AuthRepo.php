<?php

declare(strict_types=1);

namespace Auth\Api\Repository;

use Auth\Api\UserDTO;
use App\Model\ModelRefreshToken;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;
use UnexpectedValueException;

class O2AuthRepo
{
    private array $config;

    public function __construct(private ModelRefreshToken $model)
    {
        $this->config = config('o2auth');
    }

    public function generateRefreshToken($user_id): array
    {
        $data['user_agent'] = md5($_SERVER['HTTP_USER_AGENT']);
        $salt = $user_agent ?? uniqid();
        $data['token'] = sha1($salt.time().bin2hex(random_bytes(16)));
        $data['user_id'] = $user_id;

        return $data;
    }

    public function createRefreshToken($user_id): string
    {
        $data = $this->generateRefreshToken($user_id);
        $this->model->create($data);

        return $data['token'];
    }

    public function checkRefreshToken($token): stdClass|false
    {
        $user_agent = md5($_SERVER['HTTP_USER_AGENT']);
        $lifetime = $this->config['refresh_lifetime'];
        $row = $this->model->read($token, $user_agent, $lifetime);

        $this->deleteOrUpdate($token, $user_agent, $row);

        return $row;
    }
  
    public function encodeJWT(UserDTO $user, ?int $iat = null): string
    {
        $iat = $iat ?? time();

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

    private function deleteOrUpdate($token, $user_agent, &$row): void
    {
        if ($row) {
            $refresh_period = $this->config['refresh_update'];
            $last_activity = strtotime($row->updated);
            if ($last_activity + $refresh_period < time()) {
                $data = $this->generateRefreshToken($row->user_id);
                $this->model->update($token, ['token' => $data['token']]);
                $row->token = $data['token'];
            }
        } else {
            $this->model->delete($token, $user_agent);
        }
    }
}
