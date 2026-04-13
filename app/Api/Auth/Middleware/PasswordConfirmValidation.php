<?php

namespace App\Api\Auth\Middleware;

use Az\Validation\Middleware\ApiValidationMiddleware;
use App\Api\Common\Middleware\ApiContractValidation;

class PasswordConfirmValidation extends ApiContractValidation
{
    protected function setRules($request)
    {
        $path = APPPATH . 'auth/messages';
        
        $this->validation->addMsgPath($path)
            ->rule('password', 'required|password')
            ->rule('confirmPassword', 'required|confirm(:data)');
    }
}
