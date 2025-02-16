<?php

namespace Auth\Controller;

use Auth\Middleware\EmailValidation;
use Common\Email\Email;
use Sys\Form\Form;
use Az\Route\Route;
use HttpSoft\Response\RedirectResponse;

class Restore extends AuthAbstract
{
    public array $mailData;

    public function form(Form $form)
    {
        $form->title('Restore password');
        $form->form('@auth/email')->id('emailform')
            ->action(path('restore', ['action' => 'message']));

        $form->text('email')
            ->placeholder('Enter the email you provided');

        return $form->render();
    }

    #[Route(methods: 'post')]
    #[EmailValidation]
    public function message()
    {
        $user = $this->model->getUser();
        
        $this->session->uid = $user->id;
        $this->session->code = bin2hex(random_bytes(16));

        (new Email)->to($user)->tpl('restore')->send();

        $data['title'] = 'Restore';
        $data['data'] = config('messages', 'restore.info');
        $data['data']['username'] = $user->name;

        return view('@auth/common/alert', $data);
    }

    public function confirm($code)
    {
        if ($code && $code === $this->session->get('code')) {
            $uri = path('restore.password', ['action' => 'form']);
            return new RedirectResponse($uri);
        }

        $data['title'] = 'Restore';
        $data['data'] = config('messages', 'restore.whoops');

        return view('@auth/common/alert', $data);
    }
}
