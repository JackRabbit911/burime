<?php declare(strict_types=1);

namespace App\Burime\Repository;

use App\Burime\Model\BranchAuthor;
use App\Burime\Model\ModelPost;
use Common\Enum\BranchAuthorPermissions;
use Common\Enum\BranchStatus;
use Common\Enum\PostStatus;
use Common\Enum\BranchAuthorStatus;

class SaveRepo
{
    private ModelPost $modelPost;
    private BranchAuthor $modelBranchAuthor;

    public function __construct(ModelPost $modelPost, BranchAuthor $modelBranchAuthor)
    {
        $this->modelPost = $modelPost;
        $this->modelBranchAuthor = $modelBranchAuthor;
    }

    public function addAuthor(int $branch_id, int $author_id, int $user_id): void
    {
        $data['branch_id'] = $branch_id;
        $data['author_id'] = $author_id;
        $data['user_id'] = $user_id;
        $data['role'] = BranchAuthorPermissions::WRITE->value;
        $data['status'] = BranchAuthorStatus::member->value;

        $this->modelBranchAuthor->addAuthor($data);
    }

    public function save($data)
    {
        $this->modelPost->save($data);
    }
}
