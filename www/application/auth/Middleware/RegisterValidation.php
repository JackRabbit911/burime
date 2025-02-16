<?php

namespace Auth\Middleware;

use Attribute;
use Auth\Model\ModelUser;
use Az\Validation\Validation;
use Az\Validation\Middleware\ValidationMiddleware;

#[Attribute]
final class RegisterValidation extends ValidationMiddleware
{
    private ModelUser $model;

    public function __construct(Validation $validation, ModelUser $model)
    {
        parent::__construct($validation);
        $this->model = $model;
    }

    protected function setRules($request)
    {
        $path = APPPATH . 'auth/messages';
        
        $this->validation->addMsgPath($path)
            ->rule('name', 'required|username')
            ->rule('email', 'required|email')
            ->rule('email', [$this->model, 'isUniqueEmail'])
            ->rule('password', 'required|password')
            ->rule('confirm', 'required|confirm(:data)')
            ->rule('agree', 'yes|boolean');
    }

    protected function modifyData($data)
    {
        unset($data['confirm']);
        return $data;
    }
}
