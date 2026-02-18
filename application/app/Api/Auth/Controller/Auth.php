<?php

declare(strict_types=1);

namespace App\Api\Auth\Controller;

use App\Api\Auth\Middleware\AuthValidation;
use Az\Route\Route;
use App\Api\Auth\Middleware\AuthMiddleware;
use App\Api\Auth\Service\OAuth;

class Auth extends ApiAuthController
{
    #[Route(methods: 'post')]
    #[AuthValidation]
    #[AuthMiddleware]
    public function login()
    {
        return $this->request->getAttribute('user') ? true : false;
    }

    public function logout(OAuth $oauth)
    {
        $oauth->logout();
        return true;
    }
}
