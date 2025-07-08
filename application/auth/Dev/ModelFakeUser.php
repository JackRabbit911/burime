<?php

declare(strict_types=1);

namespace App\Dev;

use Sys\Model\MysqlModel;

class ModelFakeUser extends MysqlModel
{
    public function insert($data)
    {
        $this->qb->table('users')
            ->insert($data);
    }
}
