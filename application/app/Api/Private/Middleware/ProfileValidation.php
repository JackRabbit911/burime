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
        $user = $request->getAttribute('user');
        $path = APPPATH . 'auth/messages';
        
        $this->validation->addMsgPath($path)
            ->rule('name', 'required|username')
            ->rule('email', 'required|email')
            ->rule('email', [$this->model, 'isUniqueOrOwnEmail'], $user->email)
            ->rule('dob', 'validDate')
            ->rule('phone', 'phone')
            ->rule('sex', 'integer')
            ->rule('file', 'size(1M)|img');
    }
}
