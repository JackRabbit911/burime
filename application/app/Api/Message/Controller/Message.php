<?php

declare(strict_types=1);

namespace App\Api\Message\Controller;

use App\Api\Common\Controller\ApiContractController;
use App\Api\Message\Repository\MsgRepo;

class Message extends ApiContractController
{
    public function __construct(private MsgRepo $repo){}

    public function list()
    {
        return $this->repo->getList($this->user->id);
    }

    public function show(int $id)
    {
        return $this->repo->getMessage($id);
    }
}
