<?php declare(strict_types=1);

namespace Auth\Component;

use Sys\Form\Form;

class AuthForm extends Form
{
    public function __construct()
    {
        $this->title('Sign in to your account');
        $this->form('@auth/auth')
            ->action(path('auth', ['action' => 'login']))
            ->id('authform');

        $this->text('email');
        $this->password();
        $this->checkbox('remember')
            ->label('Remember me')->checked(true);
    }
}
