<?php

declare(strict_types=1);

namespace Common\Controller;

use Sys\Controller\WebController;
use Auth\Middleware\AuthGuardRedirect;

#[AuthGuardRedirect]
class Front extends WebController
{
    public function __invoke($id = null)
    {
        $this->app->js('/config/my.js');
        $this->app->js('/assets/js/main.js');
        return view('common/front', ['title' => 'Personal account']);
    }
}
