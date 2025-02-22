<?php declare(strict_types=1);

namespace App\Burime\Controller;

use App\Author\Model\ModelAuthor;
use App\Burime\Model\ModelPost;
use App\Burime\Service\PostPermissions;
use App\Burime\Middleware\TimeUpMiddleware;
use Common\Enum\AuthorRole;
use Common\Enum\BranchStatus;
use Common\Enum\PostStatus;
use Common\Enum\BranchAuthorStatus;
use Sys\Controller\WebController;
use Az\Route\Route;

#[TimeUpMiddleware]
class PostBranchSave extends WebController
{
    private ModelPost $modelPost;

    public function __construct(ModelPost $modelPost)
    {
        $this->modelPost = $modelPost;
    }

    #[Route(tokens: ['branch_id' => '\d+', 'post_id' => '\d*'])]
    #[Route(methods: 'post')]
    public function save(ModelAuthor $modelAuthor, $branch_id, $post_id = null)
    {
        $branch = $this->request->getAttribute('branch');
        $data = $this->request->getParsedBody();

        $post_permissions = new PostPermissions($branch, $this->user);

        if ($branch->info['current_writer'] === $this->user->id) {
            switch ($data['sbmt']) {
                case 'publish':
                    if ($branch->info['moderation'] === 1
                        && !$post_permissions->hasRole(AuthorRole::Moderator->value)) {
                        $branch->status = BranchStatus::Blocked->value;
                        $post_status = PostStatus::Moderation->value;
                    } else {
                        $branch->status = BranchStatus::Ready->value;
                        $post_status = PostStatus::Publish->value;
                    }

                    if (!$branch->authors->has($data['author'])) {
                        $author = $modelAuthor->find((int) $data['author']);
                        $author->role = AuthorRole::Author->value;
                        $author->status = BranchAuthorStatus::Participant->value;
                        $branch->authors->push($author);
                    }

                    $branch->info['time_up'] = false;
                    $branch->info['time_beguin'] = null;
                    $this->modelPost->markAsExpired($branch_id, $post_id);
                    break;
                case 'draft':
                    $post_status = PostStatus::Draft->value;

                    if ($branch->info['time_up']) {
                        $this->session->flash('msg', __('Time to write expired. Hurry up to publish your post'));
                    }

                    $this->modelPost->markAsExpired($branch_id, $post_id);
                    break;
                case 'cancel':
                    if (!$post_id) {
                        $branch->status = BranchStatus::Ready->value;
                    }

                    $post_status = false;
                    break;
            }
        }

        if ($post_status !== false) {
            $post_data = [
                'author_id' => (int) $data['author'],
                'body' => $data['new_post'],
                'status' => $post_status,
                'branch_id' => $branch_id,
            ];

            if ($post_id) {
                $post_data['id'] = $post_id;
            }

            if ($branch->info['current_writer'] === $this->user->id) {
                $this->modelPost->save($post_data);
            }
        }

        $branch->save();

        return $this->redirect(path('branch', ['branch_id' => $branch_id]));
    }
}
