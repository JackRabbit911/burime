<?php

declare(strict_types=1);

namespace App\Branch\Api\Model;

use Common\Enum\PostStatus;
use Sys\Model\MysqlModel;

class ModelBranchSave extends MysqlModel
{
    const MAX_WEIGHT = 65535;

    private $tablePosts;
    private $branchesPosts;

    public function saveBranch(array $branch)
    {
        $id = $this->qb->table('branches')
            ->onDuplicateKeyUpdate($branch)
            ->insert($branch);

        return $id ?? $branch['id'] ?? 0;
    }

    public function saveBranchAuthors(array $authors, int $branch_id)
    {
        array_walk($authors, function(&$v, $k, $branch_id) {
            $v['branch_id'] = $branch_id;
        }, $branch_id);

        $table = $this->qb->table('branches_authors');

        $table->where('branch_id', '=', $branch_id)
            ->delete();

        $table->insert($authors);
    }

    public function sg($data)
    {
        $t = $this->qb->table('branches_genres');
        $t->where('branch_id', '=', 25)->delete();
        $t->insert($data);
    }

    public function saveBranchGenres(array $genres, int $branch_id)
    {
        $data = array_map(fn($v) => [
                'branch_id' => $branch_id,
                'genre_id' => $v,
            ], $genres);

        $table = $this->qb->table('branches_genres');

        $table->where('branch_id', '=', $branch_id)
            ->delete();

        $table->insert($data);
    }

    public function saveBranchPosts(array $posts, int $branch_id)
    {
        $this->tablePosts = $this->qb->table('posts');
        $this->branchesPosts = $this->qb->table('branches_posts');

        if (!empty($posts['first'])) {
            $this->setPost($posts['first'], $branch_id, 1);
        }

        if (!empty($posts['last'])) {
            $this->setPost($posts['last'], $branch_id, self::MAX_WEIGHT);
        }
    }

    private function setPost(array $post, int $branch_id, int $weight)
    {
        $post['status'] = PostStatus::Approved->value;

        $post_id = $this->tablePosts
            ->onDuplicateKeyUpdate($post)
            ->insert($post);

        $this->branchesPosts->insertIgnore([
            'branch_id' => $branch_id,
            'post_id' => $post['id'] ?? $post_id,
            'weight' => $weight,
        ]);
    }
}
