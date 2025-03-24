<?php declare(strict_types=1);

namespace App\Burime\Controller;

use App\Burime\Post as EntityPost;
use App\Burime\Model\ModelPost;
use App\Burime\Repository\BranchRepo;
use App\Burime\Service\PostPermissions;
use App\Burime\Service\PostDelMsg;

use Common\Contract\BranchInterface;
use Common\Enum\AuthorRole;
use Common\Enum\BranchStatus;
use Common\Enum\PostStatus;

use Auth\User;
use HttpSoft\Response\RedirectResponse;
use Sys\Controller\BaseController;

class PostControls extends BaseController
{
    private ModelPost $modelPost;
    private BranchRepo $branchRepo;
    private PostPermissions $permissions;
    private BranchInterface $branch;
    private EntityPost $post;
    private ?User $user;

    private bool $isModerator;
    private bool $isAuthor;
    private string $uri;

    public function __construct(ModelPost $modelPost, BranchRepo $branchRepo)
    {
        $this->modelPost = $modelPost;
        $this->branchRepo = $branchRepo;
    }

    protected function _before()
    {
        $this->user = $this->request->getAttribute('user');
        $this->branch = $this->branchRepo->find($this->parameters['branch_id']);
        $this->permissions = new PostPermissions($this->branch, $this->user);
        $this->post = $this->modelPost->findPost($this->parameters['post_id'], $this->parameters['branch_id']);

        $this->isModerator = $this->permissions->hasRole(AuthorRole::Moderator->value);
        $this->isAuthor = $this->permissions->isAuthor($this->post);

        $this->uri = path('branch', ['branch_id' => $this->branch->id]);
    }

    public function delete(PostDelMsg $message, $branch_id, $post_id)
    {
        if ($this->permissions->delete($this->post)) {
            $is_delete = $this->modelPost->delete($post_id);

            if ($is_delete && $this->isModerator && !$this->isAuthor) {
                $message->send($this->branch, $this->post, $this->user->id);
            }
        }
       
        return new RedirectResponse($this->uri);
    }

    public function approve(int $branch_id, int $post_id)
    {
        $this->modelPost->setPostStatus($post_id, PostStatus::Publish->value);
        $this->branchRepo->setStatus($branch_id, BranchStatus::Ready->value);

        return new RedirectResponse($this->uri);
    }
}
