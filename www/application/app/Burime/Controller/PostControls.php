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
                $this->session->flash('body', $this->makeBody(8));

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

    private function makeBody($count_words)
    {
        $substr = Text::catStr($this->post->body, $count_words);
        $created = $this->post->created;
        $author = $this->author->alias;

        return <<<EOD
        Ув., $author! Ваш пост
        от $created
        "$substr..."
        был удалён модератором.
        Причина:
        EOD;
    }
}
