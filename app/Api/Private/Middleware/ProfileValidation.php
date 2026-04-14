<?php

namespace App\Api\Private\Middleware;

use App\Api\Common\Middleware\ApiContractValidation;
use Auth\Model\ModelUser;

class ProfileValidation extends ApiContractValidation
{
    private ModelUser $model;

    public function __construct(ModelUser $model)
    {
        $this->model = $model;
    }

    protected function setRules($request)
    {
        $email = $this->data['email'];
        $path = APPPATH . 'auth/messages';
        
        $this->validation->addMsgPath($path)
            ->rule('name', 'required|username')
            ->rule('email', 'required|email')
            ->rule('email', [$this->model, 'isUniqueOrOwnEmail'], $email)
            ->rule('dob', 'validDate')
            ->rule('phone', 'phone')
            ->rule('sex', 'integer')
            ->rule('file', 'size(1M)|img');
    }
}
