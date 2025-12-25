<?php

declare(strict_types=1);

namespace App\Api\Private\Model;

use Sys\Model\MysqlModel;

class ModelDrafts extends MysqlModel
{
    public function get(int $user_id)
    {
        return $this->qb->table('drafts')
            ->select('id', 'title')
            ->where('owner', '=', $user_id)
            ->get();
    }

    public function getCount(int $user_id)
    {
        return $this->qb->table('drafts')
            ->select('id')
            ->where('owner', '=', $user_id)
            ->count();
    }
}
