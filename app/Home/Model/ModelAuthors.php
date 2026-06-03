<?php

declare(strict_types=1);

namespace App\Home\Model;

use Common\Contract\AuthorInterface;
use Common\Enum\MemberRole;
use Sys\Model\MysqlModel;

class ModelAuthors extends MysqlModel
{
    public function get(
        ?int $limit = null,
        int $offset = 0,
        string $filter = '',
        string $search = '',
        ?int $user_id = null,
    ) {
        $table = $this->qb->table('authors')
            ->select('authors.*')
            ->select($this->qb->raw('COUNT(child_id) AS c_members'))
            ->leftJoin('authors_authors', 'parent_id', '=', 'id')
            ->groupBy('authors.id');

        $role = MemberRole::getByFilter($filter) ?? 0;

        if ($filter === 'authors') {
            $table->where('authors.openclosed', '=', 2);
        } elseif ($filter === 'groups') {
            $table->where('authors.openclosed', '<', 2);
        } elseif ($role) {
            $table->join('users_authors', 'users_authors.author_id', '=', 'authors.id')
                ->where('users_authors.role', '=', $role)
                ->where('user_id', '=', $user_id);
        }

        if ($search) {
            $table->where($this->qb->raw('MATCH(alias) AGAINST(?)', [$search]));
        }

        $count = $table->count();

        if ($limit) {
            $table->limit($limit)->offset($offset);
        }

        $authorClassName = container()->get(AuthorInterface::class);

        return [$table->asObject($authorClassName)->get(), $count];
    }

    public function getCount()
    {
        return $this->qb->table('authors')->count();
    }
}
