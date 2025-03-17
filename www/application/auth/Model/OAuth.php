<?php declare(strict_types = 1);

namespace Auth\Model;

use Auth\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ServerRequestInterface;

class OAuth
{
    private ModelUser $modelUser;
    private TokenAuth $tokenAuth;
    private array $config;

    public function __construct(ModelUser $modelUser, TokenAuth $tokenAuth)
    {
        $this->modelUser = $modelUser;
        $this->tokenAuth = $tokenAuth;
        $this->config = config('o2auth');
    }

    public function auth(ServerRequestInterface $request)
    {
        $jwt = $request->getCookieParams()[$this->config['name']] ?? null;
        $payload = ($jwt) ? $this->decode($jwt) : null;

        if ($payload) {
            $user = User::fromArray((array) $payload->user);
        } else {
            $user_id = $this->tokenAuth->auth();
            if ($user_id) {
                $user = $this->modelUser->find($user_id);
                unset($user->password);
            }
        }

        if (isset($user)) {
            $now = time();

            if ($now - $payload?->iat > 60) {
                $jwt = $this->encode($user, $now);
                $options = $this->config['cookie'];
                $options['expires'] = $now + $this->config['lifetime'];
                setcookie($this->config['name'], $jwt, $options);
            }           
        }

        return $user ?? null;
    }

    public function login(User $user)
    {
        $now = time();
        $jwt = $this->encode($user, $now);

        $options = $this->config['cookie'];
        $options['expires'] = time() + $this->config['lifetime'];

        unset($user->password);
        
        setcookie($this->config['name'], $jwt, $options);
    }

    public function logout()
    {
        $options = $this->config['cookie'];
        $options['expires'] = time() - $this->config['lifetime'];

        setcookie($this->config['name'], '', $options);
    }

    private function encode(User $user, ?int $iat = null)
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
                'phone' => $user->phone,
                'dob' => $user->dob,
                'sex' => $user->sex,
            ]
        ];

        return JWT::encode($payload, $this->config['key'], $this->config['algo']);
    }

    private function decode(string $jwt)
    {
        try {
            return JWT::decode($jwt, new Key($this->config['key'], $this->config['algo']));
        } catch (Exception $e) {
            return null;
        }
    }
}
