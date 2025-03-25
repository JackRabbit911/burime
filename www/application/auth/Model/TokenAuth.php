<?php

namespace Auth\Model;

use Auth\Model\ModelUserToken;
use Psr\Http\Message\ServerRequestInterface;

final class TokenAuth
{
    private ServerRequestInterface $request;
    private $cookieName = 'UAT';
    private $model;
    private $options = [
        'expires'   => 0,
        'path'      => '/',
        'secure'    => false,
        'httponly'  => true,
    ];

    public function __construct(ModelUserToken $userTokenModel, ServerRequestInterface $request)
    {
        $this->model = $userTokenModel;
        $this->request = $request;
    }

    public function auth()
    {
        $user_id = false;
        $token = $this->request->getCookieParams()[$this->cookieName] ?? null;

        if ($token) {
            $user_id = $this->model->read($token);
        }

        if ($user_id) {
            $token = $this->model->update($token);
            setcookie($this->cookieName, $token, $this->options);
        } elseif ($token) {
            $this->model->delete($token);
        }

        return $user_id;
    }

    public function remember($key, $user_id)
    {
        $remember = (isset($this->request->getParsedBody()[$key])) ? true : false;

        $user_agent = $this->request->getServerParams()['HTTP_USER_AGENT'] ?? null;
        $user_agent = ($user_agent) ? md5($user_agent) : null;

        if ($remember) {          
            $token = $this->model->create($user_agent, $user_id);
            setcookie($this->cookieName, $token, $this->options);
        }
    }

    public function forget($cookies)
    {
        $token = $cookies[$this->cookieName] ?? null;
        setcookie($this->cookieName, '', $this->options);

        if ($token) {
            $this->model->delete($token);
        }
    }
}
