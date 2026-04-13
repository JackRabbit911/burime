<?php

declare(strict_types=1);

namespace App\Api\Auth\Middleware;

use App\Api\Auth\Model\ModelAuth;
use App\Api\Auth\Model\ModelRecovery;
use App\Api\Common\Middleware\ApiContractValidation;

class EmailValidation extends ApiContractValidation
{
    public function __construct(private ModelRecovery $model){}

    protected function setRules($request)
    {
        $this->validation
            ->rule('email', 'required|email')
            ->rule('email', [$this->model, 'isRegisteredEmail'])
            ->addMsgPath(APPPATH . 'auth/messages');
    }
}
