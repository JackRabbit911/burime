<?php declare(strict_types=1);

namespace App\Burime\Controller;

use App\Burime\Service\PostPermissions;
use App\Burime\Middleware\TimeUpMiddleware;
use App\Burime\Repository\SaveRepo;
use Common\Enum\AuthorRole;
use Common\Enum\BranchStatus;
use Common\Enum\PostStatus;
use Common\Middleware\AuthGuard;
use Sys\Controller\WebController;
use Az\Route\Route;
use HttpSoft\Response\RedirectResponse;

#[AuthGuard]
#[TimeUpMiddleware]
class PostBranchSave extends WebController
{
    #[Route(tokens: ['branch_id' => '\d+', 'post_id' => '\d*'])]
    #[Route(methods: 'post')]
    public function __invoke(SaveRepo $repo, $branch_id, $post_id = null)
    {
        $branch = $this->request->getAttribute('branch');
                
        if ($branch->info['current_writer'] === $this->user->id) {
            $data = $this->request->getParsedBody();
            $post_permissions = new PostPermissions($branch, $this->user);

            switch ($data['sbmt']) {
                case 'publish':
                    if ($branch->info['moderation'] === 1
                        && !$post_permissions->hasRole(AuthorRole::Moderator->value)) {
                        $branch->status = BranchStatus::Waiting->value;
                        $post_status = PostStatus::Moderation->value;
                        $branch->info['time_beguin'] = time();
                    } else {
                        $branch->status = BranchStatus::Ready->value;
                        $post_status = PostStatus::Approved->value;
                    }

                    $repo->addAuthor($branch->id, (int) $data['author'], $this->user->id);

                    // $branch->info['time_up'] = false;
                    // $branch->info['time_beguin'] = null;

                    break;
                case 'draft':
                    $post_status = PostStatus::Draft->value;

                    if ($branch->info['time_up']) {
                        $this->session->flash('msg', __('Time to write expired. Hurry up to publish your post'));
                    }

                    break;
                case 'cancel':
                    if (!$post_id) {
                        $branch->status = BranchStatus::Ready->value;
                        $branch->info['time_up'] = false;
                        $branch->info['time_beguin'] = null;
                    }

                    $post_status = false;
                    break;
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
                    $repo->save($post_data);
                }
            }

            $branch->save();
        }

        return new RedirectResponse(path('branch', ['branch_id' => $branch_id]));
    }
}
