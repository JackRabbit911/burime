<?php

declare(strict_types=1);

namespace Common\Controller;

use Sys\Controller\WebController;

class Front extends WebController
{
    public function __invoke($id = null)
    {
        // $title = $id ? 'Edit branch' : 'Create branch';
        // $this->app->js('/assets/js/branch.js');
        return view('common/front');
    }
}
