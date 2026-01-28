<?php

declare(strict_types=1);

namespace App\Api\Message\Model;

use Sys\Model\MysqlModel;

class ModelSaveMessage extends MysqlModel
{
    public function saveMessage(array $data): int
    {
        return (int) $this->qb->table('messages')
            ->insert($data);
    }

    public function saveRcipients(array $data): void
    {
        $this->qb->table('messages_authors')
            ->insert($data);
    }

    public function setStatus(int $id, array $recipients, int $status): void
    {
        $this->qb->table('messages_authors')
            ->where('message_id', '=', $id)
            ->whereIn('author_id', $recipients)
            ->update(['status' => $status]);
    }

    public function deleteMsgLink(int $id, int $recipient)
    {
        $this->qb->table('messages_authors')
            ->where('message_id', '=', $id)
            ->where('author_id', '=', $recipient)
            ->delete();
    }

    public function deleteMsg($id)
    {
        $this->qb->table('messages')
            ->where('id', '=', $id)
            ->delete();
    }
}
