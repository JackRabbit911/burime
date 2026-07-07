<?php

declare(strict_types=1);

namespace Auth\Api\Middleware;

use Auth\Api\Model\ModelRecovery;
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
