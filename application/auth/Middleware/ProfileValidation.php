<?php

namespace Auth\Middleware;

use Attribute;
use Auth\Model\ModelUser;
use Az\Validation\Validation;
use Az\Validation\Middleware\ValidationMiddleware;
use Psr\Http\Message\ServerRequestInterface;

#[Attribute]
final class ProfileValidation extends ValidationMiddleware
{
    private ModelUser $model;

    public function __construct(Validation $validation, ModelUser $model)
    {
        parent::__construct($validation);
        $this->model = $model;
    }

    protected function setRules($request)
    {
        $user = $request->getAttribute('user');
        $user = $this->model->find($user->id);

        $path = APPPATH . 'auth/messages';
        
        $this->validation->addMsgPath($path)
            ->rule('name', 'required|username')
            ->rule('email', 'required|email')
            ->rule('email', [$this->model, 'isUniqueOrOwnEmail'], $user->email)
            ->rule('dob', 'validDate')
            ->rule('phone', 'phone')
            ->rule('sex', 'integer')
            ->rule('avatar', 'size(1M)|img');
    }

    protected function modifyData($data)
    {
        if (empty($data['phone'])) {
            $data['phone'] = null;
        }

        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/\D+/', '', $data['phone']);
        };

        if ($data['dob'] === '') {
            $data['dob'] = null;
        };

        return $data;
    }

    // protected function debug(ServerRequestInterface $request)
    // {
    //     dd($this->validation->getResponse());
    // }
}
