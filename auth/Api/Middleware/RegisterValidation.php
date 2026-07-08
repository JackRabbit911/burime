<?php

declare(strict_types=1);

namespace Auth\Api\Middleware;

use Auth\Api\Model\ModelUser;
use App\Api\Common\Middleware\ApiContractValidation;

class RegisterValidation extends ApiContractValidation
{
    public function __construct(private ModelUser $model){}

    protected function setRules($request)
    {
        $this->validation
            ->rule('name', 'required|username')
            ->rule('email', 'required|email')
            ->rule('email', [$this->model, 'isUniqueEmail'])
            ->rule('password', 'required|password')
            ->rule('confirmPassword', 'required|confirm(:data)')
            ->rule('agree', 'required|boolean')
            ->addMsgPath(APPPATH . 'auth/messages');
    }
}
