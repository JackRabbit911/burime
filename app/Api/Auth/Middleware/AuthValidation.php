<?php

declare(strict_types=1);

namespace App\Api\Auth\Middleware;

use App\Api\Auth\Model\ModelAuth;
use App\Api\Common\Middleware\ApiContractValidation;

class AuthValidation extends ApiContractValidation
{
    public function __construct(private ModelAuth $model){}

    protected function setRules($request)
    {
        $this->validation
            ->rule('email', 'required|email')
            ->rule('password', 'required|password')
            ->rule('password', [$this->model, 'isPairEmailPswd'], ':email')
            ->rule('remember', 'boolean')
            ->addMsgPath(APPPATH . 'auth/messages');
    }
}
