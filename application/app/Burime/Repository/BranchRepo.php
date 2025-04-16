<?php declare(strict_types=1);

namespace App\Burime\Repository;

use App\Burime\Model\FindBranch;
use App\Burime\Model\ModelPost;
use Common\Contract\BranchInterface;
use Sys\Collection\Collection;

class BranchRepo
{
    private FindBranch $modelBranch;
    private ModelPost $modelPost;

    public function __construct(FindBranch $modelBranch, ModelPost $modelPost)
    {
        $this->modelBranch = $modelBranch;
        $this->modelPost = $modelPost;
    }

    public function find($id): ?BranchInterface
    {
        if (!$id) {
            return null;
        }
        
        $branch = ($this->modelBranch->findBranch($id)) ?: null;

        if (!$branch) {
            return null;
        }

        $authors = $this->modelBranch->getAuthorsByBranch($id);
        $branch->authors = new Collection($authors);

        $max_weight_count = $this->modelPost->getMaxWeightAndCount($id);
        $branch->maxWeight = $max_weight_count->max_weight;
        $branch->postsCount = $max_weight_count->count;

        return $branch;
    }

    public function setStatus(int $branch_id, int $status)
    {
        $this->modelBranch->setStatus($branch_id, $status);
    }

    public function setPostStatusPublish($branch_id)
    {
        $this->modelPost->setPostStatusPublish($branch_id);
    }
}
