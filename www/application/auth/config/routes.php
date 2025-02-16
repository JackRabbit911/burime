<?php declare(strict_types=1);

use Auth\Controller\Auth;
use Auth\Controller\ChangePassword;
use Auth\Controller\Register;
use Auth\Controller\Restore;
use Auth\Controller\Profile;
use App\Private\PrivateController;
use Auth\Controller\Demo;

return [
    // 'demo'          => ['/', Demo::class],
    'auth'          => ['/auth/{action}', Auth::class],
    'register'      => ['/auth/register/{action}/{code?}', Register::class],
    'restore'       => ['/auth/restore/{action}/{code?}', Restore::class],
    'restore.password'=>['/auth/restore/change/password/{action}', ChangePassword::class],

    'profile'       => ['/private/profile', [Profile::class, 'form']],
    'private'       => ['/private/{action}', PrivateController::class],
    'profile.save'  => ['/private/profile/save', [Profile::class, 'save']],
    'profile.password'=>['/private/change/password/{action}', ChangePassword::class]
];
