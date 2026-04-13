<?php

declare(strict_types=1);

namespace App\Api\Message\Repository;

use App\Api\Message\Model\ModelAdditional;

class BranchRepo
{
    public function __construct(private ModelAdditional $model){}

    public function getBranchCover(int $id)
    {
        $branch = $this->model->getBranchCover($id);
        $branch->cover = json_decode($branch->cover);
        $branch->alias = $this->model->getAuthorAlias($id);

        return $branch;
    }
}
