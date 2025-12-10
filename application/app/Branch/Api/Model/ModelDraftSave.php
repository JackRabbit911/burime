<?php

declare(strict_types=1);

namespace App\Branch\Api\Model;

use Sys\Model\MysqlModel;

class ModelDraftSave extends MysqlModel
{
    public function save(array $data)
    {
        $id = $this->qb->table('drafts')
            ->onDuplicateKeyUpdate($data)
            ->insert($data);

        return (int) $id ?? (int) $data['id'] ?? 0;
    }
}
