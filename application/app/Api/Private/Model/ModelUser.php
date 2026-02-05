<?php

declare(strict_types=1);

namespace App\Api\Private\Model;

use Sys\Model\MysqlModel;

class ModelUser extends MysqlModel
{
    public function find(int $id)
    {
        return $this->qb->table('users')
            ->select('id', 'name', 'email', 'phone', 'dob', 'sex')
            ->find($id);
    }
    
    public function update(string $hash, int $user_id)
    {
        return $this->qb->table('users')
            ->where('id', '=', $user_id)
            ->update(['password' => $hash]);
    }
}
