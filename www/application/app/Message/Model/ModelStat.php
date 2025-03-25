<?php declare(strict_types=1);

namespace App\Message\Model;

use Sys\Model\Trait\QueryBuilder;

final class ModelStat 
{
    use QueryBuilder;

    public function getNewMsgCount($user_id)
    {
        return $this->qb->table('messages_authors')
            ->where('user_id', '=', $user_id)
            ->where('status', '>=', 100)
            ->count();
    }
}
