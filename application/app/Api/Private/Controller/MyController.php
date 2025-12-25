<?php

declare(strict_types=1);

namespace App\Api\Private\Controller;

use App\Api\Common\Controller\ApiContractController;
use App\Api\Private\Model\ModelBooks;
use App\Api\Private\Model\ModelDrafts;
use App\Api\Private\Repository\StatRepo;

class MyController extends ApiContractController
{
    public function stat(StatRepo $repo)
    {
        return $repo->get($this->user->id);
    }

    public function books(ModelBooks $model)
    {
        return $model->get($this->user->id);
    }

    public function drafts(ModelDrafts $model)
    {
        return $model->get($this->user->id);
    }
}
