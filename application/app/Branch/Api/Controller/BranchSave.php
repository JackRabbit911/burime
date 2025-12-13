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
#[BranchValidation]
class BranchSave extends ApiContractController
{
    public function __invoke(BranchSaveRepo $repo)
    {
        return $this->saveHandler($repo);
    }

    public function draft(DraftSaveRepo $repo)
    {
        return $this->saveHandler($repo);
    }

    private function saveHandler($repo)
    {
        $post = $this->request->getParsedBody();
        $files = $this->request->getUploadedFiles();

        $branch_id = $repo->save($post, $files, $this->user->id);

        return ['id' => $branch_id];
    }
}
