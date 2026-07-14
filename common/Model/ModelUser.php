<?php

declare(strict_types = 1);

namespace Common\Model;

use Sys\Model\MysqlModel;
use PDO;

class ModelUser extends MysqlModel
{
    public function getOwnAuthorsIds(int $user_id): array
    {
        return $this->qb->table('authors')
            ->select('id')
            ->where('owner', '=', $user_id)
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->get();
    }
}
