<?php declare(strict_types=1);

namespace App\Burime\Model;

use App\Burime\Post;
use Common\Enum\PostStatus;
use Sys\Model\Interface\Saveble;
use Sys\Model\Trait\Find;
use Sys\Model\Trait\QueryBuilder;
use Sys\Model\Trait\Schema;

class ModelPost implements Saveble
{
    use QueryBuilder;
    use Schema;
    use Find;

    const MAX_WEIGHT = 65535;

    protected string $table = 'posts';
    protected string  $entityClass = Post::class;

    public function save($post, $last = false)
    {
        if (!is_array($post)) {
            $post = $post->toArray();;
        }

        if (!empty($post['id'])) {
            $this->update($post);
        } else {
            $branch_id = $post['branch_id'];
            unset($post['branch_id']);

            $weight = (isset($post['last']) && $post['last'] === true) ? self::MAX_WEIGHT - 1 : 
                $this->qb->table('branches_posts')
                    ->where('branch_id', '=', $branch_id)
                    ->where('weight', '<', self::MAX_WEIGHT)
                    ->max('weight');

            unset($post['last']);

            $post['id'] = $this->insert($post, $branch_id, ++$weight);
        }

        return $post['id'];
    }

    /**
     * PostControls
     */
    public function delete($post_id)
    {
        $count = $this->qb->table('branches_posts')
            ->where('post_id', '=', $post_id)
            ->count();

        if ($count > 1) {
            return false;
        }
        
        $this->qb->table($this->table)
            ->where('id', '=', $post_id)
            ->delete();

        return true;
    }

    /**
     * PostControls
     */
    public function setPostStatus($post_id, int $status)
    {
        $this->qb->table($this->table)
            ->where('id', '=', $post_id)
            ->update(['status' => $status]);
    }

    /**
     * PostSave
     */
    public function MarkAsExpired($branch_id, $post_id = null): void
    {
        $this->qb->table('branches_posts')
            ->select('posts.*')
            ->join('posts', 'posts.id', '=', 'branches_posts.post_id')
            ->where('branch_id', '=', $branch_id)
            ->where('posts.status', '=', PostStatus::Draft->value)
            ->where('post_id', '!=', (int) $post_id)
            ->update(['posts.status' => 10]);
    }

    /**
     * AuthorPostGuard
     * PostControls
     */
    public function findPost($post_id, $branch_id = null)
    {
        $post = $this->find($post_id);

        if ($post && $branch_id) {
            $post->weight = $this->getWeight($post_id, $branch_id);
        }

        return $post;
    }

    public function get($branch_id, $limit, $offset)
    {
        return $this->qb->table('branches_posts')
            ->select('posts.*', 'branches_posts.weight')
            ->select($this->qb->raw('AVG(rating) AS rating'))
            ->select('authors.alias')
            ->join('posts', 'posts.id', '=', 'post_id')
            ->leftJoin('posts_ratings', 'posts_ratings.post_id', '=', 'posts.id')
            ->join('authors', 'authors.id', '=', 'posts.author_id')
            ->where('branch_id', '=', $branch_id)
            ->asObject(Post::class)
            ->groupBy('posts.id')
            ->limit($limit)->offset($offset)
            ->orderBy('weight')->get();
    }

    public function getList($branch_id, $user_id, $limit, $offset)
    {
        $table = $this->qb->table('branches_posts')
            ->select('posts.*', 'branches_posts.weight')
            ->select($this->qb->raw('AVG(posts_ratings.rating) AS rating'))
            ->select('authors.alias')
            ->join('posts', 'posts.id', '=', 'post_id')
            ->leftJoin('posts_ratings', 'posts_ratings.post_id', '=', 'posts.id')
            ->leftJoin($this->qb->raw('posts_ratings AS pr'), 'pr.post_id', '=', 'branches_posts.post_id')
            ->join('authors', 'authors.id', '=', 'posts.author_id')
            ->where('branch_id', '=', $branch_id)
            ->asObject(Post::class)
            ->groupBy('posts.id');

        if ($limit) {
            $table->limit($limit);

            if ($offset) {
                $table->offset($offset);
            }
        }
        
        return $table->orderBy('weight')->get();
    }

    public function getPostsRatingByUser($branch_id, $user_id)
    {
        return $this->qb->table('branches_posts')
            ->select('branches_posts.post_id', 'rating')
            ->leftJoin('posts_ratings', 'posts_ratings.post_id', '=', 'branches_posts.post_id')
            ->where('branches_posts.branch_id', '=', $branch_id)
            ->where('posts_ratings.user_id', '=', $user_id)
            ->setFetchMode(\PDO::FETCH_KEY_PAIR)
            ->get();
    }

    public function getFirstLastPosts($branch_id)
    {
        return $this->qb->table('branches_posts')
            ->select('posts.id', 'posts.body')
            ->join('posts', 'posts.id', '=', 'post_id')
            ->where('branch_id', '=', $branch_id)
            ->where(function ($qb) {
                $qb->where('weight', '=', self::MAX_WEIGHT);
                $qb->orWhere($qb->raw("weight = (SELECT MIN(weight) FROM branches_posts)"));
            })->orderBy('weight')->get();
    }

    //self
    public function getWeight($post_id, $branch_id)
    {
        return $this->qb->table('branches_posts')
            ->select('weight')
            ->where('branch_id', '=', $branch_id)
            ->setFetchMode(\PDO::FETCH_COLUMN)
            ->find($post_id, 'post_id');
    }

    //AuthorPostGuard
    public function getMaxWeight($branch_id)
    {
        $max_weight = $this->qb->table('branches_posts')
            ->where('branch_id', '=', $branch_id)
            ->where('weight', '<', self::MAX_WEIGHT)
            ->max('weight');

        return (int) $max_weight;
    }

    public function getMaxWeightAndCount($branch_id)
    {
        return $this->qb->table('branches_posts')
            ->select($this->qb->raw('MAX(weight) AS max_weight'))
            ->select($this->qb->raw('COUNT(post_id) AS count'))
            ->where('weight', '<', self::MAX_WEIGHT)
            ->find($branch_id, 'branch_id');
    }

    //AuthorPostGuard
    public function getLast($branch_id)
    {
        return $this->qb->table('branches_posts')
            ->select('posts.*', 'post_id', 'weight')
            ->join('posts', 'posts.id', '=', 'post_id')
            ->where('posts.status', '>=', PostStatus::Publish->value)
            ->where('branch_id', '=', $branch_id)
            ->asObject(Post::class)
            ->orderBy('weight', 'DESC')
            ->limit(1)
            ->get()[0] ?? null;
    }

    public function setRating($data)
    {
        return $this->qb->table('posts_ratings')
            ->onDuplicateKeyUpdate($data)
            ->insert($data);
    }

    public function getPostsAuthorsByBranch($branch_id)
    {
        return $this->qb->table('branches_posts')
            ->selectDistinct('posts.author_id')
            ->join('posts', 'posts.id', '=', 'branches_posts.post_id')
            ->where('branches_posts.branch_id', '=', $branch_id)
            ->setFetchMode(\PDO::FETCH_COLUMN)
            ->get();
    }

    private function insert($post, $branch_id, $weight)
    {
        $data = array_intersect_key((array) $post, array_flip($this->columns($this->table)));

        $post_id = $this->qb->table($this->table)
            ->insert($data);

        $chain = [
            'branch_id' => $branch_id,
            'post_id' => $post_id,
            'weight' => $weight,
        ];

        $this->qb->table('branches_posts')->insert($chain);

        return $post_id;
    }

    private function update($post)
    {
        $status = PostStatus::from($post['status']);

        if (!$status->allowChange()) {
            return false;
        }

        $data = array_intersect_key((array) $post, array_flip($this->columns($this->table)));

        $this->qb->table($this->table)
            ->where('id', '=', $data['id'])
            ->update($data);

        return $post['id'];
    }
}
