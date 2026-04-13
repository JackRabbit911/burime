<?php

declare(strict_types=1);

namespace App\Api\Author\Model;

use Sys\Model\MysqlModel;
use PDO;

class ModelAuthorDelete extends MysqlModel
{
    public function delete(int $author_id)
    {
        $this->qb->table('authors')
            ->where('id', '=', $author_id)
            ->delete();
    }

    public function setAuthorStatus(int $author_id, int $status)
    {
        
    }

    public function getAlias(int $author_id): string
    {
        return $this->qb->table('authors')
            ->select('alias')
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->find($author_id);
    }

    public function getCountPosts(int $author_id): int
    {
        return $this->qb->table('posts')
            ->select('id')
            ->where('author_id', '=', $author_id)
            ->count();
    }

    public function getCountMembers(int $author_id): int
    {
        return $this->qb->table('authors_authors')
            ->select('child_id')
            ->where('parent_id', '=', $author_id)
            ->count();
    }
}
