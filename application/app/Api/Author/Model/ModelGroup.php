<?php

declare(strict_types=1);

namespace App\Api\Author\Model;

use Sys\Model\MysqlModel;

class ModelGroup extends MysqlModel
{
    public function getMembers($group_id)
    {
        return $this->qb->table(['authors_authors' => 'aa'])
            ->select($this->qb->raw('aa.child_id as id'))
            ->select('aa.role', 'aa.status', 'authors.alias')
            ->join('authors', 'authors.id', '=', 'child_id')
            ->where('parent_id', '=', $group_id)
            ->get();
    }
}
