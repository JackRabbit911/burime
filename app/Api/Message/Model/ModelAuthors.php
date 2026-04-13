<?php

declare(strict_types=1);

namespace App\Api\Message\Model;

use Sys\Model\MysqlModel;
use PDO;

class ModelAuthors extends MysqlModel
{
    public function findAuthor(int $id)
    {
        return $this->qb->table('authors')
            ->select('id', 'alias')
            ->setFetchMode(PDO::FETCH_ASSOC)
            ->find($id);
    }

    public function getAuthorsByIds(array $ids)
    {
        return $this->qb->table('authors')
            ->select('id', 'alias')
            ->whereIn('id', $ids)
            ->setFetchMode(PDO::FETCH_ASSOC)
            ->get();
    }
}
