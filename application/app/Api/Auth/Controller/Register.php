<?php

declare(strict_types=1);

namespace App\Api\Auth\Controller;

use App\Api\Auth\Middleware\PasswordConfirmValidation;
use App\Api\Auth\Service\OAuth;
use App\Api\Auth\Service\TokenAuth;
use App\Api\Auth\Model\ModelRecovery;
use App\Api\Auth\Middleware\AuthMiddleware;
use App\Api\Auth\Middleware\AuthValidation;
use App\Api\Auth\Middleware\EmailValidation;
use App\Api\Auth\Model\ModelUser;
use App\Api\Auth\Service\SendEmail;
use Az\Route\Route;

#[Route(methods: 'post')]
class Register extends ApiAuthController
{
    public function __construct(private ModelUser $model){}

    #[PasswordConfirmValidation]
    public function savepswd()
    {
        $data = $this->request->getBody()->getContents();
        $data= json_decode($data);

        $hash = password_hash($data->password, PASSWORD_DEFAULT);
        $this->model->update(['password' => $hash], $data->id);

        return true;
    }
}
