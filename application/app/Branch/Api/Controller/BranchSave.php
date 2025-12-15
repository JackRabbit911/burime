<?php

declare(strict_types=1);

namespace App\Branch\Api\Controller;

use App\Branch\Api\Repository\BranchSaveRepo;
use App\Branch\Api\Repository\DraftSaveRepo;
use App\Branch\Api\Middleware\BranchValidation;
use App\Branch\Api\Middleware\AuthGuard;
use Auth\Middleware\OAuthMiddleware;
use Az\Route\Route;
use Sys\Middleware\PreparePostData;

#[OAuthMiddleware]
#[AuthGuard]
#[Route(methods: 'post')]
#[PreparePostData]
class BranchSave extends ApiContractController
{
    private array $post = [];

    public function __construct(private DraftSaveRepo $draftSaveRepo) {}

    #[BranchValidation]
    public function __invoke(BranchSaveRepo $repo)
    {
        $post = $this->request->getParsedBody();
        $draft_id = $post['draft'] ?? 0;
        $this->draftSaveRepo->delete($draft_id);

        return $this->saveHandler($repo, $post);
    }

    #[BranchValidation]
    public function draft()
    {
        return $this->saveHandler($this->draftSaveRepo);
    }

    #[Route(methods: 'delete')]
    public function delete(int $id)
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
