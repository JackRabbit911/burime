<?php declare(strict_types=1);

namespace App\Burime\Repository;

use App\Burime\Model\BranchAuthor;
use App\Burime\Model\ModelPost;
use Common\Enum\BranchStatus;
use Common\Enum\PostStatus;
use Common\Enum\AuthorRole;
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
        // $post_permissions = new PostPermissions($branch, $user);

        // if ($branch->info['moderation'] === 1
        //     && !$post_permissions->hasRole(AuthorRole::Moderator->value)) {
        //     $branch->status = BranchStatus::Blocked->value;
        //     $post_status = PostStatus::Moderation->value;
        // } else {
        //     $branch->status = BranchStatus::Ready->value;
        //     $post_status = PostStatus::Publish->value;
        // }

        $data['branch_id'] = $branch_id;
        $data['author_id'] = $author_id;
        $data['user_id'] = $user_id;
        $data['role'] = AuthorRole::Author->value;
        $data['status'] = BranchAuthorStatus::member->value;

        $this->modelBranchAuthor->addAuthor($data);

        // return $post_status;
    }

    public function save($data)
    {
        $this->modelPost->save($data);
    }
}
