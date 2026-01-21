<?php

declare(strict_types=1);

namespace App\Api\Message\Controller;

use App\Api\Common\Controller\ApiContractController;
use App\Api\Message\Repository\MsgRepo;
use Az\Route\Route;
use HttpSoft\Response\EmptyResponse;

class Message extends ApiContractController
{
    public function __construct(private MsgRepo $repo){}

    public function list()
    {
        return $this->repo->getList($this->user->id);
    }

    public function show(int $id)
    {
        $message = $this->repo->getMessage($id, $this->user->id);

        return $message ?: new EmptyResponse(404);
    }

    #[Route(methods: 'delete')]
    public function delete(int $id, int $recipient)
    {
        $this->repo->deleteIncommingMessage($id, $recipient);

        return "Message $id for $recipient was deleted";
    }

    #[Route(methods: 'delete')]
    public function remove(int $id)
    {
        $this->repo->deleteSendedMessage($id);
        
        return "Message $id was removed";
    }
}
