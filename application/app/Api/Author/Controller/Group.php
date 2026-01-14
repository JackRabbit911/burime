<?php

declare(strict_types=1);

namespace App\Api\Author\Controller;

use App\Api\Author\Model\ModelGroup;
use App\Api\Common\Controller\ApiContractController;

class Group extends ApiContractController
{
    public function __construct(private ModelGroup $model) {}

    public function members($id = null)
    {
        return $id ? $this->model->getMembers($id) : [];
    }
}
