<?php

declare(strict_types=1);

namespace Auth\Api\Middleware;

use Auth\Api\Model\ModelAuth;
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
