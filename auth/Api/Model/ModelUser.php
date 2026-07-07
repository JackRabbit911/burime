<?php

declare(strict_types=1);

namespace Auth\Api\Model;

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

    public function isUniqueEmail(string $email): bool
    {
        $user_id = $this->qb->table('users')
            ->select('id')
            ->find($email, 'email');

        return $user_id ? false : true;
    }
}
