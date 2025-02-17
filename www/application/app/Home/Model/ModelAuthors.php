<?php declare(strict_types = 1);

namespace App\Home\Model;

use Common\Contract\AuthorInterface;
use Sys\Model\Model;

class ModelAuthors extends Model
{
    public function get($limit = null, $offset = 0, $groupsOnly = false)
    {
        $table = $this->qb->table('authors')
            ->select('authors.*')
            // ->select($this->qb->raw('AVG(authors_ratings.rating) AS rating'))
            ->select($this->qb->raw('COUNT(child_id) AS c_members'))
            ->leftJoin('authors_authors', 'parent_id', '=', 'id')
            // ->leftJoin('authors_ratings', 'author_id', '=', 'id')
            ->groupBy('authors.id')
            // ->orderBy('rating', 'DESC')
            ;

        if ($groupsOnly) {
            $table->where('openclosed', '<', 2);
        }

        if ($limit) {
            $table->limit($limit)->offset($offset);
        }
        
        $authorClassName = container()->get(AuthorInterface::class);

        return $table->asObject($authorClassName)->get();
    }

    public function getCount()
    {
        return $this->qb->table('authors')->count();
    }
}
