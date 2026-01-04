<?php

declare(strict_types=1);

namespace App\Api\Author\Model;

use Sys\Model\MysqlModel;

class ModelSaveDelete extends MysqlModel
{
    public function save(array $data): int
    {
        $id = $this->qb->table('authors')
            ->onDuplicateKeyUpdate($data)
            ->insert($data);

        return $id ? (int) $id : (int) $data['id'];
    }
}
