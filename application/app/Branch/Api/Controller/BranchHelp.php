<?php

declare(strict_types=1);

namespace App\Branch\Api\Controller;

use App\Branch\Api\Repository\HelpRepo;

class BranchHelp extends ApiContractController
{
    public function __invoke(HelpRepo $repo, int $step)
    {
        return $repo->getHelp($step);
    }
}
