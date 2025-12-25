<?php declare(strict_types = 1);

namespace Common\Model;

use Sys\Model\MysqlModel;
use PDO;

class ModelUserStat extends MysqlModel
{
    public function getMsgCount($ids)
    {
        if (empty($ids)) {
            return ['total' => 0, 'new' => 0];
        }
        
        $table = $this->qb->table('messages_authors')
            ->whereIn('author_id', $ids);

        $count['total'] = $table->count();

        $count['new'] = $table
            ->where('status', '>=', 100)
            ->count();

        return $count;
    }

    public function getUserComplete($user_id)
    {
        $sql = "SELECT 45 +
        (IF(dob IS NOT NULL AND TRIM(dob) <> '',15,0)) + 
        (IF(sex IS NOT NULL AND TRIM(sex) <> '',15,0)) +
        (IF(info IS NOT NULL AND TRIM(info) <> '',10,0)) AS complete
        FROM users WHERE id = ?";

        return $this->qb->query($sql, [$user_id])
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->first();            
    }
}
