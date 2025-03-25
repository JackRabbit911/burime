<?php declare(strict_types=1);

namespace Common\Repository;

use App\Branch\Model\ModelBranch;
use App\Burime\Model\ModelPost;
use Common\Enum\PostStatus;

class BranchPostCreateRepo
{
    private ModelBranch $modelBranch;
    private ModelPost $modelPost;

    public function __construct(ModelBranch $modelBranch, ModelPost $modelPost)
    {
        $this->modelBranch = $modelBranch;
        $this->modelPost = $modelPost;
    }

    public function save($branch, $post)
    {    
        $branch_id = $this->modelBranch->save($branch);   
        
        if (!empty($post['first_post'])) {
            $first = [
                'id' => (int) $post['first_id'] ?? null,
                'author_id' => $branch->master->id,
                'body' => $post['first_post'],
                'status' => PostStatus::Publish->value,
                'branch_id' => $branch_id,
            ];

            $this->modelPost->save($first);
        }

        if (!empty($post['last_post'])) {
            $last = [
                'id' => (int) $post['last_id'] ?? null,
                'author_id' => $branch->master->id,
                'body' => $post['last_post'],
                'status' => PostStatus::Publish->value,
                'branch_id' => $branch_id,
                'last' => true,
            ];

            $this->modelPost->save($last);
        }

        return $branch_id;
    }
}
