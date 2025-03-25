<?php declare(strict_types=1);

namespace Auth\Component;

use Auth\User;
use Sys\Form\Form;

class ProfileForm extends Form
{
    public function __construct(User $user)
    {
        $this->title('User profile');
        $this->form('@auth/profile')
            ->id('profileform')
            ->action(path('profile.save'));

        $this->text('name')->placeholder('Username')->value($user->name);
        $this->text('email')->value($user->email);
        $this->tel('phone')->value($user->phone);
        $this->date('dob')->label('Date of birth')->value($user->dob);
        $this->radio('sex')->value($user->sex);
        $this->file('avatar')
            ->label('Pick a file for avatar')
            ->alt_label('Up to 1Mb');
    }
}
