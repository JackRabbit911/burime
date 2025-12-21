<?php

declare(strict_types=1);

namespace App\Api\Private\Controller;

use App\Api\Common\Controller\ApiContractController;
use App\Api\Private\Model\ModelBooks;

class MyController extends ApiContractController
{
    public function books(ModelBooks $model)
    {
        return $model->get($this->user->id);
    }
}
