<?php

declare(strict_types=1);

namespace App\Message\Model;

use Sys\Model\MysqlModel;

class HasAuthor extends MysqlModel
{
    public function has(int $user_id)
    {
        $countOwnAuthors = $this->qb->table('authors')
            ->select('id')
            ->where('owner', '=', $user_id)
            ->count();

        return $countOwnAuthors > 0;
    }
}
