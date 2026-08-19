<?php

declare(strict_types=1);

namespace Auth\Api\Controller;

use Auth\Api\Middleware\AuthMiddleware;
use Auth\Api\Middleware\AuthValidation;
use Auth\Api\Repository\AuthRepo;
use Az\Route\Route;
use Memcached;

class Auth extends ApiAuthController
{
    private $config = [];

    public function __construct(private AuthRepo $repo)
    {
        $this->config = config('o2auth');
    }


    #[Route(methods: 'post')]
    #[AuthValidation]
    public function login()
    {
        $now = time();
        [$user, $refresh, $bearer] = $this->repo->login($this->data);
        $options = $this->config['cookie'];

        $lifetime = $this->data->remember
            ? $this->config['remember_lifetime']
            : $this->config['refresh_lifetime'];

        $options['expires'] = $now + $this->config['lifetime'];
        setcookie('OAT', $bearer, $options);

        $options['expires'] = $now + $lifetime;
        setcookie('UAT', $refresh, $options);

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


    #[Route(methods: 'delete')]
    public function logoutOthers()
    {
        $token = $this->request->getCookieParams()['UAT'] ?? null;

        if (!$token) {
            return false;
        }

        $this->repo->logoutOthers($token);

        return 'GoodBye';
    }

    private function _logout(string $func)
    {
        $csrf = $this->data->csrf ?? null;
        $token = $this->request->getCookieParams()['UAT'] ?? null;

        if (!$token) {
            return 'Goodbye';
        }

        call_user_func([$this->repo, $func], $token, $csrf);

        $options = $this->config['cookie'];
        $options['expires'] = time() - 3600;

        setcookie('OAT', '', $options);
        setcookie('UAT', '', $options);

        return 'Goodbye';
    }
}
