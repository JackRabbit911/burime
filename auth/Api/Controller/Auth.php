<?php

declare(strict_types=1);

namespace Auth\Api\Controller;

use Auth\Api\Middleware\AuthValidation;
use Auth\Api\Repository\AuthRepo;
use Az\Route\Route;
use HttpSoft\Response\EmptyResponse;

class Auth extends ApiAuthController
{
    private $config = [];

    public function __construct(private AuthRepo $repo)
    {
        $this->config = config('o2auth');
    }

    public function auth(): string | EmptyResponse
    {
        $refresh = $this->request->getCookieParams()['UAT'] ?? false;
        $bearer = $this->request->getCookieParams()['OAT'] ?? false;

        $root_jwt = $this->repo->authRoot($bearer);

        if ($root_jwt) {
            return $root_jwt;
        }

        if (!$refresh) {
            return new EmptyResponse(401);
        }

        [$user, $jwt] = $this->repo->auth($refresh);

        if (!$user) {
            return new EmptyResponse(401);
        }

        if (!$user->role) {
            return new EmptyResponse(403);
        }

        return $jwt;
    }

    #[Route(methods: 'post')]
    #[AuthValidation]
    public function login()
    {
        $now = time();
        [$user, $refresh, $bearer] = $this->repo->login($this->data);
        $options = $this->config['cookie'];

        $bearer_lifetime = $user->id === 0
            ? $this->config['roottime']
            : $this->config['lifetime'];

        $options['expires'] = $now + $bearer_lifetime;
        setcookie('OAT', $bearer, $options);

        if ($refresh) {
            $refresh_lifetime = $this->data->remember
                ? $this->config['remember_lifetime']
                : $this->config['refresh_lifetime'];

            $options['expires'] = $now + $refresh_lifetime;
            setcookie('UAT', $refresh, $options);
        }

        return [
            'user' => $user,
            'bearer' => $bearer,
        ];
    }

    #[Route(methods: 'delete')]
    public function logout()
    {
        return $this->_logout('logout');
    }

    #[Route(methods: 'delete')]
    public function quit()
    {
        return $this->_logout('logoutGlobal');
    }

    #[Route(methods: 'post')]
    public function refresh()
    {
        $refresh = $this->request->getCookieParams()['UAT'] ?? null;

        if (!$refresh) {
            return new EmptyResponse(401);
        }

        $result = $this->repo->rotate($refresh);

        if ($result) {
            if (isset($result->lifetime)) {
                $options = $this->config['cookie'];
                $options['expires'] = time() + $result->lifetime;
                setcookie('UAT', $result->token, $options);
            }

            return $result->bearer;
        }

        return new EmptyResponse(403);
    }

    // #[Route(methods: 'delete')]
    // public function logoutOthers()
    // {
    //     $token = $this->request->getCookieParams()['UAT'] ?? null;

    //     if (!$token) {
    //         return false;
    //     }

    //     $this->repo->logoutOthers($token);

    //     return 'GoodBye';
    // }

    private function _logout(string $func)
    {
        $options = $this->config['cookie'];
        $options['expires'] = time() - 3600;
        setcookie('OAT', '', $options);

        $token = $this->request->getCookieParams()['UAT'] ?? null;

        if (!$token) {
            return 'Goodbye';
        }

        $csrf = $this->data->csrf ?? null;
        call_user_func([$this->repo, $func], $token, $csrf);
        setcookie('UAT', '', $options);

        return 'Goodbye';
    }
}
