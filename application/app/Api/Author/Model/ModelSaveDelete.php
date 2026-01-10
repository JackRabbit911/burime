<?php

declare(strict_types=1);

namespace App\Api\Author\Model;

use Sys\Model\MysqlModel;

class ModelSaveDelete extends MysqlModel
{
    public function saveAuthor(array $data): int
    {
        $id = $this->qb->table('authors')
            ->onDuplicateKeyUpdate($data)
            ->insert($data);

        return $id ? (int) $id : (int) $data['id'];
    }

    public function saveMembers(int $author_id, array $members)
    {
        array_walk($members, function(&$v, $k, $author_id) {
            $v['parent_id'] = $author_id;
        }, $author_id);

        $table = $this->qb->table('authors_authors');

        $table->where('parent_id', '=', $author_id)
            ->delete();

        $table->insert($members);
    }
}
