<?php

declare(strict_types=1);

namespace App\Api\Message\Repository;

use App\Api\Common\Model\ModelAuthors;
use App\Api\Message\Enum\MsgStatus;
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

    public function getMessage(int $id, int $user_id)
    {
        $ids = $this->modelAuthors->getOwnAuthorsIds($user_id);
        
        if (!$this->modelMessage->isOut($id, $ids)) {
            $message = $this->modelMessage->findIncoming($id, $ids);

            if (!$message) {
                return null;
            }
            
            $message->incoming = true;
            $this->modelMessage->setStatus($id, $ids, MsgStatus::Read->value);
        } else {
            $message = $this->modelMessage->findSended($id, $ids);

            if (!$message) {
                return null;
            }

            $message->incoming = false;
        }


        if (json_validate($message->data)) {
            $message->data = json_decode($message->data);
        }

        return $message;
    }

    public function deleteIncommingMessage(int $id, int $recipient)
    {
        $this->modelMessage->deleteMsgLink($id, $recipient);
    }

    public function deleteSendedMessage(int $id)
    {
        $this->modelMessage->deleteMsg($id);
    }
}
