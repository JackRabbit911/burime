<?php declare(strict_types=1);

namespace App\Burime\Model;

use Common\Enum\BranchAuthorStatus;
use Sys\Model\Trait\QueryBuilder;

final class BranchAuthor
{
    use QueryBuilder;
    
    public function setAuthorStatus(int $branch_id, int $author_id, int $user_id, BranchAuthorStatus $status)
    {
        $this->qb->table('branches_authors')
            ->where('branch_id', '=', $branch_id)
            ->where('author_id', '=', $author_id)
            ->update(['user_id' => $user_id, 'status' => $status->value]);
    }

    public function addAuthor($data)
    {
        $this->qb->table('branches_authors')
            ->insertIgnore($data);
    }

    public function deleteAuthor(int $branch_id, int $author_id)
    {
        $this->qb->table('branches_authors')
            ->where('branch_id', '=', $branch_id)
            ->where('author_id', '=', $author_id)
            ->delete();
    }
}
