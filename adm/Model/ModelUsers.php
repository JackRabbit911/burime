<?php

declare(strict_types=1);

namespace Adm\Model;

use Adm\Service\UserDTO;
use Sys\Model\MysqlModel;

class ModelUsers extends MysqlModel
{
    private string $table = 'users';

    public function get(?int $limit = null, int $offset = 0, ?string $filter = null, ?string $search = null)
    {
        $table = $this->qb->table($this->table)
            ->select('id', 'name', 'role')
            ->leftJoin('admins', 'admins.user_id', '=', 'id');

        $result['total'] = $table->count();

        // if ($filter) {
        //     $table->where('name', 'LIKE', "%$filter%");
        // }

        if (!empty($search)) {
            $table->where($this->qb->raw('MATCH(name) AGAINST(? IN BOOLEAN MODE)', [$search]));
        }

        $result['selected'] = $table->count();


        if ($limit) {
            $table->limit($limit)
                ->offset($offset);
        }

        $result['list'] = $table
            ->asObject(UserDTO::class)
            ->get();

        return $result;
    }

    public function create(array $data) {}

    public function read(int $id)
    {
        return $this->qb->table($this->table)
            ->select('id', 'name', 'email', 'phone', 'dob', 'sex', 'role', 'created')
            ->leftJoin('admins', 'admins.user_id', '=', 'id')
            ->find($id);
    }

    public function update(int $id, array $data) {}

    public function delete(int $id) {}
}
