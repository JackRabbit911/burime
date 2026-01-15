<?php

declare(strict_types=1);

namespace App\Api\Message\Controller;

use App\Api\Common\Controller\ApiContractController;
use App\Api\Message\Repository\MsgRepo;

class Message extends ApiContractController
{
    public function list(MsgRepo $repo)
    {
        return $repo->getList($this->user->id);
    }
}
