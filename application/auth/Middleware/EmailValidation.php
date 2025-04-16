<?php

namespace Auth\Middleware;

use Attribute;
use Auth\Model\ModelUser;
use Az\Validation\Validation;
use Az\Validation\Middleware\ValidationMiddleware;

#[Attribute]
final class EmailValidation extends ValidationMiddleware
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

        $this->validation
            ->rule('email', 'required|email')
            ->rule('email', [$this->model, 'isRegisteredEmail'])
            ->addMsgPath($path);
    }
}
