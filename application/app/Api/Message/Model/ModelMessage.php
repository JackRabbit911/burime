<?php

declare(strict_types=1);

namespace App\Api\Message\Model;

use Sys\Model\MysqlModel;

class ModelMessage extends MysqlModel
{
    public function getInbox($ids)
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

    public function getOutbox($ids)
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

    public function getDeleted($ids)
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

    public function find($id)
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
            ->find($id, 'messages.id');
    }
}
