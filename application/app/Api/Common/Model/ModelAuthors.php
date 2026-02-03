<?php

declare(strict_types=1);

namespace App\Api\Common\Model;

use Common\Enum\MemberRole;
use Sys\Model\MysqlModel;
use PDO;

class ModelAuthors extends MysqlModel
{
    public function getByGroup(int $group_id)
    {
        return $this->qb->table('authors_authors')
            ->select('authors.id', 'authors.alias')
            ->join('authors', 'authors.id', '=', 'authors_authors.child_id')
            ->where('authors_authors.parent_id', '=', $group_id)
            ->get();
    }

    public function getOwnAuthorsIds(int $user_id)
    {
        return $this->qb->table('authors')
            ->select('id')
            ->where('owner', '=', $user_id)
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->get();
    }

    public function getOwnAuthors(int $user_id)
    {
        return $this->qb->table('authors')
            ->select('id', 'alias')
            ->where('owner', '=', $user_id)
            ->get();
    }

    public function getByFilter(
        int $user_id,
        int $limit,
        int $offset,
        ?string $filter = null,
        ?string $search = null,
        array $except = []
    )
    {
        $role = MemberRole::getByFilter($filter);

        $table = $this->qb->table('authors')
            ->selectDistinct('id')
            ->select('alias');

        if ($role > 1) {
            $table->join('users_authors', 'users_authors.author_id', '=', 'authors.id')
                ->where('role', '=', $role)
                ->where('user_id', '=', $user_id);
        } elseif ($role === 1) {
            $table->where('authors.openclosed', '<', 2);
        } else {
            $table->where('authors.openclosed', '=', 2);
        }

        if ($search) {
            $table->where('alias', 'LIKE', "%$search%");
        }

        if (!empty($except)) {
            $table->whereNotIn('id', $except);
        }

        $count = $table->count();
        $authors = $table->limit($limit)
            ->offset($offset)->get();

        return [$count, $authors];
    }
}
