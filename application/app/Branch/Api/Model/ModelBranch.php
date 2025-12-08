<?php

declare(strict_types=1);

namespace App\Branch\Api\Model;

use Sys\Model\MysqlModel;
use PDO;

class ModelBranch extends MysqlModel
{
    public function find(int $id)
    {
        $branch = $this->qb->table('branches')
            ->setFetchMode(PDO::FETCH_NAMED)
            ->find($id);

        if (!$branch) {
            return null;
        }

        $branch['info'] = json_decode($branch['info']);

        return $branch;
    }

    public function getBranchAuthors($branch_id)
    {
        return $this->qb->table('authors')
            ->select('id', 'role', 'status', 'alias')
            ->join('branches_authors', 'author_id', '=', 'id')
            ->where('branch_id', '=', $branch_id)
            ->get();
    }

    public function getBranchGenres($branch_id)
    {
        return $this->qb->table('branches_genres')
            ->select('genre_id')
            ->where('branch_id', '=', $branch_id)
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->get();
    }

    public function getGenres()
    {
        return $this->qb->table('genres')
            ->select('id', 'title', 'weight')
            ->orderBy('weight')
            ->get();
    }

    public function getTotalGenres()
    {
        return $this->qb->table('genres')
            ->select($this->qb->raw("JSON_ARRAYAGG(JSON_OBJECT('id', id, 'title', title))"))
            ->groupBy('weight')
            ->orderBy('weight')
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->get();
    }

    public function findPostByWeight(int $branch_id, int $weight)
    {
        return $this->qb->table('branches_posts')
            ->select('posts.id', 'posts.body')
            ->join('posts', 'posts.id', '=', 'post_id')
            ->where('branch_id', '=', $branch_id)
            ->find($weight, 'weight');
    }
}
