<?php

declare(strict_types=1);

namespace App\Home\Controller;

use App\Home\Repository\AuthorsRepo;
use Sys\Controller\WebController;

class AuthorsList extends WebController
{
    public function __invoke(AuthorsRepo $repo)
    {
        [$view, $data] = $repo->get($this->request, $this->user?->id);
        return view($view, $data);
    }
}
