<?php

declare(strict_types=1);

namespace App\Home\Repository;

use App\Home\Model\ModelWorks;
use Common\Enum\BranchRole;

class StartRepo
{
    public function __construct(private ModelWorks $model) {}

    public function getCountWorks()
    {
        $data['total'] = $this->model->getCount();
        $data['open'] = $this->model->getCount(BranchRole::Closed);

        return $data;
    }
}
