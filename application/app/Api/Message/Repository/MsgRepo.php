<?php

declare(strict_types=1);

namespace App\Api\Message\Repository;

use App\Api\Common\Model\ModelAuthors;
use App\Api\Message\Model\ModelMessage;

class MsgRepo
{
    public function __construct(
        private ModelAuthors $modelAuthors,
        private ModelMessage $modelMessage
    ) {}

    public function getList($user_id)
    {
        $ids = $this->modelAuthors->getOwnAuthorsIds($user_id);
        $data['inbox'] = $this->modelMessage->getInbox($ids);
        $data['outbox'] = $this->modelMessage->getOutbox($ids);
        $data['deleted'] = $this->modelMessage->getDeleted($ids);

        return $data;
    }

    public function getMessage(int $id)
    {
        return $this->modelMessage->find($id);
    }
}
