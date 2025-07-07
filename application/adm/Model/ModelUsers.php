<?php

declare(strict_types=1);

namespace Adm\Model;

use Sys\Model\MysqlModel;

class ModelUsers extends MysqlModel
{
    private string $table = 'users';

    public function get()
    {
        return $this->qb->table($this->table)
            ->select('id', 'name')
            ->get();
    }

    public function create(array $data){}

    public function read(int $id){}

    public function update(int $id, array $data){}

    public function delete(int $id){}
}
