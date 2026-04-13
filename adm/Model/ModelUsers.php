<?php

declare(strict_types=1);

namespace Adm\Model;

use Sys\Model\MysqlModel;

class ModelUsers extends MysqlModel
{
    private string $table = 'users';

    public function get(int $limit, int $offset, ?string $filter = null)
    {
        $table = $this->qb->table($this->table);

        if ($filter) {
            $table->where('name', 'LIKE', "%$filter%");
        }
        
        $result['total'] = $table->count();

        $table->select('id', 'name')
        ->limit($limit)
        ->offset($offset);
        
        $result['list'] = $table->get();

        return $result;
    }

    public function create(array $data){}

    public function read(int $id)
    {
        return $this->qb->table($this->table)
            ->select('id', 'name', 'dob', 'sex', 'created')
            ->find($id);
    }

    public function update(int $id, array $data){}

    public function delete(int $id){}
}
