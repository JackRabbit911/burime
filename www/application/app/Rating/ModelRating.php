<?php declare(strict_types=1);

namespace App\Rating;

use Sys\Model\Model;

final class ModelRating extends Model
{   
    public function setRating($user_id, $post_id, $rating)
    {
        $data = [
            'user_id' => $user_id,
            'post_id' => $post_id,
            'rating' => $rating,
        ];

        return $this->qb->table('posts_ratings')
            ->onDuplicateKeyUpdate($data)
            ->insert($data);
    }

    public function removeRating($user_id, $post_id)
    {
        return $this->qb->table('posts_ratings')
            ->where('user_id', '=', $user_id)
            ->where('post_id', '=', $post_id)
            ->delete();
    }

    public function getPostsRatingByUser($branch_id, $user_id)
    {
        return $this->qb->table('branches_posts')
            ->select('branches_posts.post_id', 'rating')
            ->leftJoin('posts_ratings', 'posts_ratings.post_id', '=', 'branches_posts.post_id')
            ->where('branches_posts.branch_id', '=', $branch_id)
            ->where('posts_ratings.user_id', '=', $user_id)
            // ->groupBy('branches_posts.post_id')
            ->setFetchMode(\PDO::FETCH_KEY_PAIR)
            ->get();
    }

    public function getBranchAwgRating($branch_id)
    {
        return $this->qb->table('branches_posts')
            ->select($this->qb->raw('AVG(posts_ratings.rating)'))
            ->leftJoin('posts_ratings', 'posts_ratings.post_id', '=', 'branches_posts.post_id')
            ->where('branches_posts.branch_id', '=', $branch_id)
            ->setFetchMode(\PDO::FETCH_COLUMN)
            ->first();
    }

    public function getPostAvgRating($post_id)
    {
        return $this->qb->table('posts_ratings')
            ->where('post_id', '=', $post_id)
            ->average('rating');
    }
}
