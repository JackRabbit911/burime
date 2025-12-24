<?php

declare(strict_types=1);

namespace App\Api\Branch\Model;

use Sys\Model\MysqlModel;
use PDO;

class BranchPermissions extends MysqlModel
{
    public function getRole(int $branch_id, int $user_id)
    {
        return $this->qb->table('branches_authors')
            ->select('branches_authors.role')
            ->join('authors', 'authors.id', '=', 'branches_authors.author_id')
            ->where('branch_id', '=', $branch_id)
            ->where('authors.owner', '=', $user_id)
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->first();
    }
}
