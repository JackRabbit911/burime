<?php declare(strict_types=1);

namespace App\Burime\Repository;

use App\Burime\Model\ModelPost;
use Sys\Collection\Collection;

class PostsRepo
{
    private ModelPost $model;

    public function __construct(ModelPost $model)
    {
        $this->model = $model;
    }

    public function getPosts(int $branch_id, ?int $user_id = null, ?int $current_page = null, ?int $limit = null)
    {
        $offset = ((int) $current_page - 1) * $limit;
        $posts = $this->model->getList($branch_id, $user_id, $limit, $offset);
        $user_ratings = $this->model->getPostsRatingByUser($branch_id, $user_id);

        $posts = new Collection($posts);

        return $posts->map(function ($v) use ($user_ratings) {
            $v->user_rating = ($user_ratings) ? $user_ratings[$v->id] ?? null : null;
            return $v;
        });
    }

    public function getLastPost($posts)
    {
        $last_post = $posts->last();

        return ($last_post && $last_post->weight === ModelPost::MAX_WEIGHT) ? $last_post : null;
    }
}
