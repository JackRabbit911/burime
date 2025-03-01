<?php declare(strict_types = 1);

namespace Common\Model;

use Sys\Model\Model;

class ModelUserStat extends Model
{
    public function getMsgCount($ids)
    {
        $table = $this->qb->table('messages_authors')
            ->whereIn('author_id', $ids);

        $count['total'] = $table->count();

        $count['new'] = $table
            ->where('status', '>=', 100)
            ->count();

        return $count;
    }
}
