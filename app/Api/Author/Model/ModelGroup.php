<?php

declare(strict_types=1);

namespace App\Api\Author\Model;

use Sys\Model\MysqlModel;
use PDO;

class ModelGroup extends MysqlModel
{
    public function getMembers(int $group_id): array
    {
        return $this->qb->table(['authors_authors' => 'aa'])
            ->select($this->qb->raw('aa.child_id as id'))
            ->select('aa.role', 'aa.status', 'authors.alias')
            ->join('authors', 'authors.id', '=', 'child_id')
            ->where('parent_id', '=', $group_id)
            ->get();
    }

    public function getStatus(int $parent_id, int $child_id)
    {
        return $this->qb->table('authors_authors')
            ->select('status')
            ->where('parent_id', '=', $parent_id)
            ->where('child_id', '=', $child_id)
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->first();
    }

    public function setStatus(array $data)
    {
        $table = $this->qb->table('authors_authors');

        if (!isset($data['role'])) {
            $data['role'] = $table->select('role')
                ->where('parent_id', '=', $data['parent_id'])
                ->where('child_id', '=', $data['child_id'])
                ->setFetchMode(PDO::FETCH_COLUMN)
                ->first();
        }

        $table->onDuplicateKeyUpdate($data)
            ->insert($data);
    }
}
