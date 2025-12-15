<?php declare(strict_types=1);

namespace App\Branch\Controller;

use App\Branch\Middleware\AuthorGuard;
use Sys\Controller\WebController;

#[AuthorGuard]
class Branch extends WebController
{
    public function __invoke($id = null)
    {
        $title = $id ? 'Edit branch' : 'Create branch';
        $this->app->js('/assets/js/branch.js');
        return view('branch/create/branch', ['title' => $title]);
    }
}
