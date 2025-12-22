<?php

declare(strict_types=1);

namespace App\Api\Branch\Model;

use Sys\Model\MysqlModel;
use PDO;

class ModelDraft extends MysqlModel
{
    public function get(int $id)
    {
        return $this->qb->table('drafts')
            ->setFetchMode(PDO::FETCH_NAMED)
            ->find($id);
    }

    public function save(array $data)
    {
        $id = $this->qb->table('drafts')
            ->onDuplicateKeyUpdate($data)
            ->insert($data);
       
        return $id ?? (int) $data['id'];
    }

    public function delete(int $id)
    {
        return $this->qb->table('drafts')
            ->where('id', '=', $id)
            ->delete();
    }
}
