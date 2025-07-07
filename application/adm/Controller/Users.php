<?php

declare(strict_types=1);

namespace Adm\Controller;

use Adm\Model\ModelUsers as ModelUsers;
use Sys\Controller\ApiController;

class Users extends ApiController
{
    public function __construct(private ModelUsers $model){}

    public function __invoke()
    {
        return $this->model->get();
    }
}
