<?php declare(strict_types = 1);

namespace App\Home\Model;

use App\Burime\Post;
use Common\Enum\PostStatus;
use Sys\Model\Model;

class ModelBestPost extends Model
{
    public function getPost()
    {
        return $this->qb->table('posts')
            ->select('posts.*', 'posts_ratings.rating', 'authors.alias')
            ->leftJoin('posts_ratings', 'posts_ratings.post_id', '=', 'posts.id')
            ->join('authors', 'authors.id', '=', 'posts.author_id')
            ->join('branches_posts', 'branches_posts.post_id', '=', 'posts.id')
            ->where('branches_posts.status', '>=', PostStatus::Publish->value)
            ->orderBy('posts_ratings.rating', 'DESC')
            ->orderBy('posts.created', 'DESC')
            ->asObject(Post::class)
            ->first();
    }
}
