<?php declare(strict_types=1);

namespace Auth\Component;

use Az\Session\SessionInterface;
use Sys\Form\Form;

class PasswordForm extends Form
{
    public function __construct(SessionInterface $session)
    {
        $this->title('Change password');
        $this->password()
                ->placeholder('New password');
            $this->password('confirm')
                ->placeholder('Confirm new password');

        if ($session->user_id) {
            $this->form('@auth/password')->id('passwordform')
                ->action(path('profile.password', ['action' => 'save']));
        } else {
            $this->form('@auth/password')->id('passwordform')
                ->action(path('restore.password', ['action' => 'save']));
        }
    }
}
