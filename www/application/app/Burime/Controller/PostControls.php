<?php declare(strict_types=1);

namespace App\Burime\Controller;

use App\Burime\Post as EntityPost;
use App\Burime\Model\ModelPost;
use App\Burime\Repository\BranchRepo;
use App\Burime\Service\PostPermissions;
use Common\Contract\BranchInterface;
use Common\Contract\AuthorInterface;
use Common\Enum\AuthorRole;
use Common\Enum\BranchStatus;
use Common\Enum\PostStatus;
use Common\Repository\AuthorRepo;

use Az\Session\SessionInterface;
use Sys\Contract\UserInterface;
use Sys\Controller\BaseController;
use Sys\Helper\Facade\Text;
use HttpSoft\Response\RedirectResponse;

class PostControls extends BaseController
{
    private ModelPost $modelPost;
    private BranchRepo $branchRepo;
    private AuthorRepo $authorRepo;
    private PostPermissions $permissions;
    private BranchInterface $branch;
    private EntityPost $post;
    private AuthorInterface $author;
    private ?UserInterface $user;
    private ?SessionInterface $session;

    private bool $isModerator;
    private bool $isAuthor;
    private string $uri;

    public function __construct(ModelPost $modelPost, BranchRepo $branchRepo, AuthorRepo $authorRepo)
    {
        $this->modelPost = $modelPost;
        $this->branchRepo = $branchRepo;
        $this->authorRepo = $authorRepo;
    }

    protected function _before()
    {
        $this->session = $this->request->getAttribute('session');
        $this->user = $this->request->getAttribute('user');
        $this->branch = $this->branchRepo->find($this->parameters['branch_id']);
        $this->permissions = new PostPermissions($this->branch, $this->user);
        $this->post = $this->modelPost->findPost($this->parameters['post_id'], $this->parameters['branch_id']);
        $this->author = $this->authorRepo->findAuthor($this->post->author_id);
        $this->isModerator = $this->permissions->hasRole(AuthorRole::Moderator->value);
        $this->isAuthor = $this->permissions->isAuthor($this->post);
        $i18n = $this->request->getAttribute('i18n');
        $i18n->addPath(APPPATH . 'app/Burime/i18n');

        $this->uri = path('branch', ['branch_id' => $this->branch->id]);
    }

    public function delete(int $branch_id, int $post_id)
    {        
        if ($this->permissions->delete($this->post)) {
            $this->branch->status = BranchStatus::Ready->value;

            if ($this->permissions->isLast($this->post)) {
                $this->branch->info['time_beguin'] = null;
                $this->branch->info['time_up'] = false;
            }

            $this->branch->save();

            // $is_delete = $this->modelPost->delete($post_id);
            $this->modelPost->setPostStatus($post_id, PostStatus::Deleted->value);
            $is_delete = true;

            if ($is_delete && $this->isModerator && !$this->isAuthor) {
                $this->session->flash('to', [$this->post->author_id]);
                $this->session->flash('subject', 'Your post has been removed by a moderator');
                $this->session->flash('body', __('post.deleted', $this->getDataBody()));

                return new RedirectResponse(path('message', ['action' => 'form']));
            }
        }
       
        return new RedirectResponse($this->uri);
    }

    public function approve(int $branch_id, int $post_id)
    {
        $this->modelPost->setPostStatus($post_id, PostStatus::Approved->value);
        $this->branchRepo->setStatus($branch_id, BranchStatus::Ready->value);

        return new RedirectResponse($this->uri);
    }

    public function rewrite(int $branch_id, int $post_id)
    {
        $this->branch->status = BranchStatus::Waiting->value;
        $this->branch->info['time_bequin'] = time();
        $this->branch->save();

        $this->modelPost->setPostStatus($post_id, PostStatus::Draft->value);

        $this->session->flash('to', [$this->post->author_id]);
        $this->session->flash('subject', 'Please, edit your post');
        $this->session->flash('body', __('post.rewrite', $this->getDataBody()));

        return new RedirectResponse(path('message', ['action' => 'form']));
    }

    private function getDataBody()
    {
        return [
            ':substr' => Text::catStr($this->post->body, 8),
            ':created' => $this->post->created,
            ':author' => $this->author->alias,
            ];
    }
}
