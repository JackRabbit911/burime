<?php

namespace Auth\Model;

use Auth\Model\ModelUserToken;
use Psr\Http\Message\ServerRequestInterface;

final class TokenAuth
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

    public function auth()
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

    public function remember($key, $user_id)
    {
        $remember = (isset($this->request->getParsedBody()[$key])) ? true : false;

        if ($remember) {          
            $token = $this->model->create($this->userAgent, $user_id);
            setcookie($this->cookieName, $token, $this->options);
        }
    }

    public function forget($cookies)
    {
        $token = $cookies[$this->cookieName] ?? null;
        setcookie($this->cookieName, '', $this->options);

        if ($token) {
            $this->model->delete($token, $this->userAgent);
        }
    }
}
