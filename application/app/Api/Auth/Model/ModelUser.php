<?php

declare(strict_types=1);

namespace App\Api\Auth\Model;

use Sys\Model\MysqlModel;

class ModelUser extends MysqlModel
{
    public function create(array $data)
    {
        return $this->qb->table('users')
            ->insert($data);
    }

    public function update(array $data, int $user_id): void
    {
        $this->qb->table('users')
            ->where('id', '=', $user_id)
            ->update($data);
    }
}
