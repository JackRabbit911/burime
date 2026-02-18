<?php

declare(strict_types=1);

namespace App\Api\Auth\Controller;

use App\Api\Auth\Service\OAuth;
use App\Api\Auth\Service\TokenAuth;
use App\Api\Auth\Middleware\AuthMiddleware;
use App\Api\Auth\Middleware\AuthValidation;
use App\Api\Auth\Middleware\EmailValidation;
use App\Api\Auth\Model\ModelRecovery;
use Az\Route\Route;

class Auth extends ApiAuthController
{
    #[Route(methods: 'post')]
    #[AuthValidation]
    #[AuthMiddleware]
    public function login()
    {
        return $this->request->getAttribute('user') ? true : false;
    }

    public function logout(OAuth $oauth, TokenAuth $tokenAuth)
    {
        $oauth->logout();
        $tokenAuth->forget();
        return true;
    }

    #[Route(methods: 'post')]
    #[EmailValidation]
    public function email(ModelRecovery $model)
    {
        $data = $this->request->getBody()->getContents();
        $data = json_decode($data);
        $user = $model->findByEmail($data->email);

        return $user;
    }
}
