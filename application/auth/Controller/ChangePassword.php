<?php declare(strict_types = 1);

namespace Auth\Controller;

use Auth\Component\PasswordForm;
use Auth\Middleware\PasswordConfirmValidation;
use Az\Route\Route;

class ChangePassword extends AuthAbstract
{
    public function form(PasswordForm $form)
    {
        if ($this->session->uid || $this->session->user_id) {
            return $form->render();
        }

        return $this->whoops();
    }

    #[Route(methods: 'post')]
    #[PasswordConfirmValidation]
    public function save()
    {
        $id = $this->session->uid ?? $this->session->user_id;

        if (!$id) {
            return $this->whoops();
        }

        $user = $this->model->find($id);
        $user->password = password_hash($this->request->getParsedBody()['password'], PASSWORD_DEFAULT);

        $user->save();

        $data['title'] = 'Restore';
        $data['data'] = config('messages', 'password.success');
        $data['data']['username'] = $user->name;

        return view('@auth/common/alert', $data);
    }

    private function whoops()
    {
        $data = config('messages', 'restore.whoops');
        $data['view'] = 'whoops';

        return view('@auth/message_wrapper', $data);
    }
}
