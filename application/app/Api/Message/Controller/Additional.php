<?php

declare(strict_types=1);

namespace App\Api\Message\Controller;

use App\Api\Common\Controller\ApiContractController;
use App\Api\Message\Repository\BranchRepo;

class Additional extends ApiContractController
{
    public function branch(BranchRepo $repo, int $id)
    {
        return $repo->getBranchCover($id);
    }
}
