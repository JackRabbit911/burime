<?php

declare(strict_types=1);

namespace App\Api\Private\Model;

use Common\Enum\BranchAuthorStatus;
use Sys\Model\MysqlModel;
use PDO;

class ModelAuthors extends MysqlModel
{
    public function getOwnAuthors(int $user_id)
    {
        return $this->qb->table('authors')
            ->select('id')
            ->where('owner', '=', $user_id)
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->get();
    }

    public function getGroupsCount(array $ownAuthors)
    {
        $groups = $this->qb->table('authors_authors')
            ->select('parent_id')
            ->whereIn('child_id', $ownAuthors)
            ->where('status', '>=', BranchAuthorStatus::invited->value)
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->get();

        return count(array_unique(array_merge($groups, $ownAuthors)));
    }

    public function getMyGroups($user_id, array $ownAuthors)
    {
        if (empty($ownAuthors)) {
            return [];
        }

        $groups = $this->qb->table('authors_authors')->alias('aa')
            ->select('authors.*')
            ->join('authors', 'authors.id', '=', 'aa.parent_id')
            ->whereIn('aa.child_id', $ownAuthors)
            ->where('aa.status', '>=', BranchAuthorStatus::invited->value)
            ->orderBy('openclosed', 'DESC');
            
        return $this->qb->table('authors')
            ->where('owner', '=', $user_id)
            ->orderBy('openclosed', 'DESC')
            ->union($groups)
            ->get();
    }
}
