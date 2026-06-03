<?php

declare(strict_types=1);

namespace App\Home\Controller;

use App\Home\Model\ModelWorks;
use App\Home\Repository\BooksRepo;
use App\Api\Branch\Repository\DraftRepo;
use Sys\Controller\WebController;

class Works extends WebController
{
    public function __construct(private ModelWorks $model, private DraftRepo $repo) {}

    public function __invoke(BooksRepo $repo)
    {
        [$view, $data] = $repo->get($this->request);
        return view($view, $data);
    }
}
