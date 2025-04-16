<?php declare(strict_types=1);

namespace App\Author\Model;

use Sys\Model\Model;
use PDO;

class ModelStat extends Model
{
    public function getStat($author_id)
    {
        $data['rating'] = $this->qb->table('posts')
            ->leftJoin('posts_ratings', 'posts_ratings.post_id', '=', 'posts.id')
            ->where('posts.author_id', '=', $author_id)
            ->average('rating');

        $data['books'] = $this->qb->table('branches_authors')
            ->where('author_id', '=', $author_id)
            ->where('role', '>=', 150)
            ->count();

        $data['posts'] = $this->qb->table('posts')
            ->where('author_id', '=', $author_id)
            ->count();

        return $data;
    }
}
