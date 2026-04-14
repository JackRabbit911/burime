<?php

declare(strict_types=1);

namespace App\Api\Private\Model;

use Sys\Model\MysqlModel;
use Sys\Model\Trait\Schema;

class ModelUser extends MysqlModel
{
    use Schema;

    private $table = 'users';

    public function find(int $id)
    {
        return $this->qb->table($this->table)
            ->select('id', 'name', 'email', 'phone', 'dob', 'sex')
            ->find($id);
    }
    
    public function update(array $data, int $user_id)
    {
        return $this->qb->table($this->table)
            ->where('id', '=', $user_id)
            ->update($this->prepareData($this->table, $data));
    }
}
