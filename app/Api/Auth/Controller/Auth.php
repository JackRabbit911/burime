<?php

declare(strict_types=1);

namespace App\Api\Auth\Controller;

use App\Api\Auth\Service\OAuth;
use App\Api\Auth\Service\TokenAuth;
use App\Api\Auth\Middleware\AuthMiddleware;
use App\Api\Auth\Middleware\AuthValidation;
use App\Api\Auth\Repository\AuthRepo;
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
    // #[AuthMiddleware]
    public function login()
    {
        $now = time();
        // return $this->request->getAttribute('user') ? true : false;
        [$refresh, $bearer, $remember] = $this->repo->login($this->data);
        $options = $this->config['cookie'];

        // return [$refresh, $bearer, $remember];

        $options['expires'] = $now + $this->config['lifetime'];
        setcookie('OAT', $bearer, $options);

        $options['expires'] = $now + $this->config['refresh_lifetime'];
        setcookie('UAT', $refresh, $options);

        if ($remember) {
            $options['expires'] = $now + $this->config['remember_lifetime'];
            setcookie('RMT', $remember, $options);
        }

        return true;
    }

    public function logout(Memcached $cache)
    {
        $cookie = $this->request->getCookieParams();
        $sid = $this->repo->logout($cookie);

        $options = $this->config['cookie'];
        $options['expires'] = time() - 3600;

        setcookie('OAT', '', $options);
        setcookie('UAT', '', $options);
        setcookie('RMT', '', $options);

        if ($sid) {
            $cache->set('blacklist_sid:' . $sid, 1, $this->config['lifetime']);
        }

        return true;
    }

    // public function logout(OAuth $oauth, TokenAuth $tokenAuth)
    // {
    //     $oauth->logout();
    //     $tokenAuth->forget();
    //     return true;
    // }
}
