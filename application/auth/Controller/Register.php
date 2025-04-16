<?php

namespace Auth\Controller;

use Auth\Component\RegisterForm;
use Auth\User;
use Auth\Middleware\RegisterValidation;
use Common\Email\Email;
use Az\Route\Route;

final class Register extends AuthAbstract
{
    public $mailData;

    public function form(RegisterForm $form)
    {
        return $form->render();
    }

    #[Route(methods: 'post')]
    #[RegisterValidation]
    public function message()
    {
        $userdata = $this->request->getParsedBody();
        $this->session->set('userdata', $userdata);
        $this->session->set('code', bin2hex(random_bytes(16)));

        (new Email)->to($userdata)->tpl('register')->send();

        $data['title'] = 'Register';
        $data['data'] = config('messages', 'register.info');
        $data['data']['username'] = $userdata['name'];
        
        return view('@auth/common/alert', $data);
    }

    public function confirm($code = null)
    {
        $data['title'] = 'Register';

        if ($code && $code === $this->session->get('code')) {
            $userdata = $this->session->pull('userdata');
            $userdata['password'] = password_hash($userdata['password'], PASSWORD_DEFAULT);
            
            $data['data'] = config('messages', 'register.success');
            $data['data']['username'] = $userdata['name'];
            $reponse = view('@auth/common/alert', $data);

            User::fromArray($userdata)->save();
        } else {
            $data['data'] = config('messages', 'register.whoops');
            $reponse = view('@auth/common/alert', $data);
        }

        $this->session->delete('code');
        $this->session->regenerate(true);

        return $reponse;
    }
}
