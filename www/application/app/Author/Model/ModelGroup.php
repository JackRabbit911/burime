<?php declare(strict_types=1);

namespace App\Author\Model;

use Common\Contract\IModelGroup;
use Sys\Model\Model;

final class ModelGroup extends Model implements IModelGroup
{
    public function inGroup($user_id, $author_id)
    {
        $owner = $this->qb->table('authors')
            ->select('id')
            ->where('owner', '=', $user_id)
            ->find($author_id);

        if ($owner) {
            return true;
        }

        $member = $this->qb->table('authors_authors')->alias('aa')
            ->select('aa.parent_id')
            ->join('authors', 'authors.id', '=', 'aa.child_id')
            ->where('authors.owner', '=', $user_id)
            ->where('aa.parent_id', '=', (int) $author_id)
            ->first();

        return ($member === null) ? false : true;
    }
}
