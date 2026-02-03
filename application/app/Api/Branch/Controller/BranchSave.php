<?php

declare(strict_types=1);

namespace App\Api\Branch\Controller;

use App\Api\Branch\Repository\BranchSaveRepo;
use App\Api\Branch\Repository\DraftSaveRepo;
use App\Api\Branch\Middleware\SaveGuard;
use App\Api\Branch\Middleware\DraftDeleteGuard;
use App\Api\Branch\Middleware\BranchValidation;
use App\Api\Common\Middleware\AuthGuard;
use App\Api\Common\Controller\ApiContractController;
use App\Api\Common\Repository\InviteMessageRepo;
use Auth\Middleware\OAuthMiddleware;
use Sys\Middleware\PreparePostData;
use Az\Route\Route;

#[OAuthMiddleware]
#[AuthGuard]
#[Route(methods: 'post')]
#[PreparePostData]
class BranchSave extends ApiContractController
{
    public function __construct(private DraftSaveRepo $draftSaveRepo) {}

    #[SaveGuard]
    #[BranchValidation]
    public function savebranch(BranchSaveRepo $repo, InviteMessageRepo $invite)
    {
        $post = $this->request->getParsedBody();
        $draft_id = $post['draft'] ?? 0;
        $this->draftSaveRepo->delete($draft_id);
        $id = $this->saveHandler($repo, $post);

        $invite->sendInviteToBranch($post, $id);

        return ['id' => $id];
    }

    #[SaveGuard]
    #[BranchValidation]
    public function savedraft()
    {
        $id = $this->saveHandler($this->draftSaveRepo);
        return ['id' => $id];
    }

    #[Route(methods: ['delete', 'get'])]
    #[DraftDeleteGuard()]
    public function rmdraft(int $id)
    {
        $this->draftSaveRepo->delete($id);
        return ['id' => $id];
    }

    private function saveHandler($repo, ?array $post = null)
    {
        $post = $post ?: $this->request->getParsedBody();
        $files = $this->request->getUploadedFiles();

        $branch_id = $repo->save($post, $files, $this->user->id);

        return $branch_id;
    }
}
