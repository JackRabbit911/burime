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
            ->select('alias')
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->find($id);
    }
}
