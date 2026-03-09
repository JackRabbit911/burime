<?php

declare(strict_types=1);

namespace App\Api\Message\Model;

use Sys\Model\MysqlModel;

class ModelMessage extends MysqlModel
{
    public function getInbox(array $ids): array
    {
        return $this->qb->table('messages_authors')->alias('to')
            ->select('messages.id', 'messages.created')
            ->select('from', 'subject')
            ->select('authors.alias')
            ->select('to.status')
            ->select('to.author_id')
            ->select($this->qb->raw('au.alias AS `to_alias`'))
            ->join('messages', 'messages.id', '=', 'to.message_id')
            ->join('authors', 'authors.id', '=', 'messages.from')
            ->join($this->qb->raw('authors AS au ON au.id = to.author_id'))
            ->whereIn('to.author_id', $ids)
            ->orderBy('to.status', 'DESC')
            ->orderBy('messages.created', 'DESC')
            ->get();
    }

    public function getOutbox(array $ids): array
    {
        return $this->qb->table('messages')
            ->select(['messages.id', 'subject', 'messages.created'])
            ->select($this->qb->raw('au.alias AS `from`'))
            ->select($this->qb->raw('MAX(messages_authors.status) AS `status`'))
            ->select($this->qb->raw('JSON_ARRAYAGG(authors.alias) AS `to`'))
            ->join('messages_authors', 'message_id', '=', 'messages.id')
            ->join('authors', 'authors.id', '=', 'messages_authors.author_id')
            ->join($this->qb->raw('authors AS au ON au.id = `from`'))
            ->whereIn('from', $ids)
            ->groupBy('messages.id')
            ->orderBy('status', 'DESC')
            ->orderBy('messages.created', 'DESC')
            ->get();
    }

    public function getDeleted(array $ids): array
    {
        return $this->qb->table('messages')
            ->select('messages.id', 'from', 'subject')
            ->select('authors.alias')
            ->join('authors', 'authors.id', '=', 'from')
            ->leftJoin('messages_authors', 'message_id', '=', 'messages.id')
            ->whereNull('message_id')
            ->whereIn('from', $ids)
            ->get();
    }

    public function findIncoming(int $id, array $recipients): object|null
    {
        return $this->qb->table('messages')
            ->select('messages.*')
            ->select($this->qb->raw('authors.alias AS `from_alias`'))
            ->select('messages_authors.status')
            ->select($this->qb->raw('au.id AS `to`'))
            ->select($this->qb->raw('au.alias AS `to_alias`'))
            ->join('messages_authors', 'message_id', '=', 'messages.id')
            ->join('authors', 'authors.id', '=', 'from')
            ->join($this->qb->raw('authors AS au ON au.id = messages_authors.author_id'))
            ->whereIn('messages_authors.author_id', $recipients)
            ->find($id, 'messages.id');
    }

    public function findSended(int $id, array $ownAuthors)
    {
        $message = $this->qb->table('messages')
            ->select('messages.*')
            ->select($this->qb->raw('authors.alias AS `from_alias`'))
            ->join('authors', 'authors.id', '=', 'from')
            ->find($id, 'messages.id');

        if (!$message) {
            return null;
        }

        $message->to = $this->qb->table('messages_authors')
            ->select('authors.id', 'authors.alias', 'messages_authors.status')
            ->join('authors', 'authors.id', '=', 'messages_authors.author_id')
            ->where('messages_authors.message_id', '=', $id)
            ->get();

        return $message;
    }

    public function isOut(int $id, array $ownAuthors)
    {
        return $this->qb->table('messages')
            ->select('id')
            ->where('id', '=', $id)
            ->whereIn('from', $ownAuthors)
            ->count();
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

    public function findMessage(int $id)
    {
        return $this->qb->table('messages')
            ->select('id', 'from', 'subject')
            ->find($id);
    }
}
