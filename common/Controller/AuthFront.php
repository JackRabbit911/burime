<?php

declare(strict_types=1);

namespace Common\Controller;

use Common\Middleware\GuestGuard;
use Sys\Controller\WebController;

#[GuestGuard]
class AuthFront extends WebController
{
    public function __invoke($id = null)
    {
        $this->app->js('/assets/js/auth.js');
        return view('common/front', ['title' => 'Auth']);
    }
}
