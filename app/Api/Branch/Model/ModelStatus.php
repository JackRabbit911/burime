<?php

declare(strict_types=1);

namespace App\Api\Branch\Model;

use Sys\Model\MysqlModel;
use PDO;

class ModelStatus extends MysqlModel
{
    public function getStatus(int $branch_id, int $author_id)
    {
         return $this->qb->table('branches_authors')
            ->select('status')
            ->where('branch_id', '=', $branch_id)
            ->where('author_id', '=', $author_id)
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->first();
    }

    public function setStatus(array $data)
    {
        $table = $this->qb->table('branches_authors');

        if (!isset($data['role'])) {
            $data['role'] = $table->select('role')
                ->where('branch_id', '=', $data['branch_id'])
                ->where('author_id', '=', $data['author_id'])
                ->setFetchMode(PDO::FETCH_COLUMN)
                ->first();
        }

        $table->onDuplicateKeyUpdate($data)
            ->insert($data);
    }
}
