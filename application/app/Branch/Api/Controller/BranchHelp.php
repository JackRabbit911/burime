<?php

declare(strict_types=1);

namespace App\Branch\Api\Controller;

use App\Branch\Api\Repository\HelpRepo;

class BranchHelp extends ApiContractController
{
    public function __construct(private HelpRepo $repo){}


    public function genres()
    {
        return $this->repo->genres();
    }
}
