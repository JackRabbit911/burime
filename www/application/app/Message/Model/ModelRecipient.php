<?php declare(strict_types=1);

namespace App\Message\Model;

use Common\Enum\MemberRole;
use Sys\Model\Trait\QueryBuilder;

class ModelRecipient 
{
    use QueryBuilder;

    public function getByIds(array $ids)
    {
        return ($ids) ? $this->qb->table('authors')
            ->select('id', 'alias')
            ->whereIn('id', $ids)
            ->get() : [];
    }

    public function getByFilter($filter = null, $except = [])
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
