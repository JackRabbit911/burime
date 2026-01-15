<?php

declare(strict_types=1);

namespace App\Api\Common\Model;

use Sys\Model\MysqlModel;
use PDO;

class ModelAuthors extends MysqlModel
{
    public function getByGroup(int $group_id)
    {
        return $this->qb->table('authors_authors')
            ->select('authors.id', 'authors.alias')
            ->join('authors', 'authors.id', '=', 'authors_authors.child_id')
            ->where('authors_authors.parent_id', '=', $group_id)
            ->get();
    }

    public function getOwnAuthorsIds(int $user_id)
    {
        return $this->qb->table('authors')
            ->select('id')
            ->where('owner', '=', $user_id)
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->get();
    }
}
