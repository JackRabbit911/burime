<?php

declare(strict_types=1);

namespace Adm\Model;

use Sys\Model\MysqlModel;

class ModelUsers extends MysqlModel
{
    private string $table = 'users';

    public function get(?int $limit = null, int $offset = 0, ?string $filter = null, string $search = '')
    {
        $table = $this->qb->table($this->table)
            ->select('id', 'name', 'role')
            ->leftJoin('admins', 'admins.user_id', '=', 'id');

        // if ($filter) {
        //     $table->where('name', 'LIKE', "%$filter%");
        // }

        if (!empty($search)) {
           $table->where($this->qb->raw('MATCH(name) AGAINST(?)', [$search]));
        }
        
        $result['total'] = $table->count();


        if ($limit) {
            $table->limit($limit)
                ->offset($offset);
        }
        
        $result['list'] = $table->get();

        return $result;
    }

    public function create(array $data){}

    public function read(int $id)
    {
        return $this->qb->table($this->table)
            ->select('id', 'name', 'dob', 'sex', 'role', 'created')
            ->leftJoin('admins', 'admins.user_id', '=', 'id')
            ->find($id);
    }

    public function update(int $id, array $data){}

    public function delete(int $id){}
}
