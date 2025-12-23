<?php

declare(strict_types=1);

namespace App\Api\Branch\Controller;

use App\Api\Branch\Repository\BranchSaveRepo;
use App\Api\Branch\Repository\DraftSaveRepo;
use App\Api\Branch\Middleware\BranchValidation;
use App\Api\Common\Middleware\AuthGuard;
use App\Api\Common\Controller\ApiContractController;
use Auth\Middleware\OAuthMiddleware;
use Az\Route\Route;
use Sys\Middleware\PreparePostData;

#[OAuthMiddleware]
#[AuthGuard]
#[Route(methods: 'post')]
#[PreparePostData]
class BranchSave extends ApiContractController
{
    public function __construct(private DraftSaveRepo $draftSaveRepo) {}

    #[BranchValidation]
    public function savebranch(BranchSaveRepo $repo)
    {
        $post = $this->request->getParsedBody();
        $draft_id = $post['draft'] ?? 0;
        $this->draftSaveRepo->delete($draft_id);

        return $this->saveHandler($repo, $post);
    }

    #[BranchValidation]
    public function savedraft()
    {
        return $this->saveHandler($this->draftSaveRepo);
    }

    #[Route(methods: 'delete')]
    public function deldraft(int $id)
    {
        $this->draftSaveRepo->delete($id);

        return ['id' => $id];
    }

    private function saveHandler($repo, ?array $post = null)
    {
        $post = $post ?: $this->request->getParsedBody();
        $files = $this->request->getUploadedFiles();

        $branch_id = $repo->save($post, $files, $this->user->id);

        return ['id' => $branch_id];
    }
}
