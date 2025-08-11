<?php

declare(strict_types=1);

namespace App\Branch\Api\Model;

use Sys\Model\MysqlModel;
use Common\Enum\MemberRole;

class Authors extends MysqlModel
{
    public function getByFilter(?string $filter = null, array $except = [])
    {
        $role = MemberRole::getByFilter($filter);

        $table = $this->qb->table('authors')
            ->selectDistinct('id')
            ->select('alias');

        if ($role) {
            $table->join('users_authors', 'users_authors.author_id', '=', 'authors.id')
                ->where('role', '=', $role);
        }

        if (!empty($except)) {
            $table->whereNotIn('id', $except);
        }

        return $table->get();
    }
}
