<?php

declare(strict_types=1);

namespace App\Api\Message\Controller;

use App\Api\Message\Repository\MsgRepo;
use App\Api\Message\Repository\SaveRepo;
use App\Api\Message\Middleware\MessageValidation;
use App\Api\Common\Controller\ApiContractController;
use App\Api\Message\Repository\BlankRepo;
use Sys\Middleware\PreparePostData;
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

    public function blank(BlankRepo $repo)
    {
        $params = $this->request->getQueryParams();
        $func = $params['content'];

        return $repo->$func($params);
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

    #[Route(methods: 'post')]
    #[PreparePostData]
    #[MessageValidation]
    public function save(SaveRepo $repo)
    {
        $post = $this->request->getParsedBody();
        $id = $repo->save($post);
        return ['id' => $id];
    }
}
