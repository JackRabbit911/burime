<?php

declare(strict_types=1);

namespace App\Branch\Api\Model;

use App\Branch\Api\AuthorDTO;
use Common\Enum\MemberRole;
use Sys\Model\MysqlModel;

class Authors extends MysqlModel
{
    public function getByFilter(
        ?string $filter = null,
        ?string $search = null,
        array $except = []
    )
    {
        $role = MemberRole::getByFilter($filter, $search);

        $table = $this->qb->table('authors')
            ->selectDistinct('id')
            ->select('alias');

        if ($role) {
            $table->join('users_authors', 'users_authors.author_id', '=', 'authors.id')
                ->where('role', '=', $role);
        }

        if ($search) {
            $table->where('alias', 'LIKE', "%$search%");
        }

        if (!empty($except)) {
            $table->whereNotIn('id', $except);
        }

        return $table->get();
    }

    public function getByUser($user_id)
    {
        return $this->qb->table('authors')
            ->select('id', 'alias')
            ->where('owner', '=', $user_id)
            ->asObject(AuthorDTO::class)->get();
    }
}
