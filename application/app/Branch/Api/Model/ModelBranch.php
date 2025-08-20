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

        $branch['authors'] = $this->getBranchAuthors($id);

        return $branch;
    }

    public function getBranchAuthors($branch_id)
    {
        return $this->qb->table('branches_authors')
            ->select('author_id', 'role', 'status')
            ->where('branch_id', '=', $branch_id)
            ->get();
    }

    public function getGenres()
    {
        return $this->qb->table('genres')
            ->select('id', 'title', 'weight')
            ->get();
    }
}
