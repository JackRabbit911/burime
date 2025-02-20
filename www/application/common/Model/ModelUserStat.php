<?php declare(strict_types = 1);

namespace Common\Model;

use Sys\Model\Model;

class ModelUserStat extends Model
{
    public function getMsgCount($user_id)
    {
        $table = $this->qb->table('messages_authors')
            ->where('user_id', '=', $user_id);

        $count['total'] = $table->count();

        $count['new'] = $table
            ->where('status', '>=', 100)
            ->count();

        return $count;
    }
}
