<?php

declare(strict_types=1);

namespace App\Api\Auth\Controller;

use Az\Route\Route;
use App\Api\Auth\Middleware\AuthMiddleware;
use App\Api\Auth\Service\OAuth;
use HttpSoft\Response\RedirectResponse;

class Auth extends ApiAuthController
{
    public function __construct(){}

    #[Route(methods: 'post')]
    #[AuthMiddleware]
    public function __invoke()
    {
        return $this->request->getAttribute('user') ? true : false;
    }

    public function logout(OAuth $oauth)
    {
        $oauth->logout();

        return true;

        // return new RedirectResponse('/');
    }
}
