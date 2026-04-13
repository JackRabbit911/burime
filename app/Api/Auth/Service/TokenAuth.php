<?php

namespace App\Api\Auth\Service;

use App\Api\Auth\Model\ModelUserToken;
use Psr\Http\Message\ServerRequestInterface;

class TokenAuth
{
    private string $userAgent;
    private string $cookieName = 'UAT';
    private array $options = [
        'expires'   => 0,
        'path'      => '/',
        'secure'    => false,
        'httponly'  => true,
    ];

    public function __construct(
        private ModelUserToken $model,
        private ServerRequestInterface $request)
    {
        $this->userAgent = md5($request->getServerParams()['HTTP_USER_AGENT']) ?? null;
    }

    public function auth(): int
    {
        $user_id = false;
        $token = $this->request->getCookieParams()[$this->cookieName] ?? null;

        if ($token) {
            $user_id = $this->model->read($token, $this->userAgent);
        }

        if ($user_id) {
            $token = $this->model->update($token, $this->userAgent);
            setcookie($this->cookieName, $token, $this->options);
        } elseif ($token) {
            $this->model->delete($token, $this->userAgent);
        }

        return $user_id;
    }

    public function remember(int $user_id, bool $remember): void
    {
        if ($remember) {          
            $token = $this->model->create($this->userAgent, $user_id);
            setcookie($this->cookieName, $token, $this->options);
        }
    }

    public function forget(): void
    {
        $cookies = $this->request->getCookieParams();
        $token = $cookies[$this->cookieName] ?? null;
        
        if ($token) {
            setcookie($this->cookieName, '', $this->options);
            $this->model->delete($token, $this->userAgent);
        }
    }
}
