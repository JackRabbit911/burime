<?php

declare(strict_types=1);

namespace App\Api\Common\Controller;

use App\Api\Common\Repository\HelpRepo;
use App\Api\Common\Controller\ApiContractController;

class Help extends ApiContractController
{
    public function branch(HelpRepo $repo, int $key)
    {
        $help = $repo($key);

        return $help ? $help : $this->_error('Not found', 404);
    }
}
