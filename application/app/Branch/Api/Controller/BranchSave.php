<?php

declare(strict_types=1);

namespace App\Branch\Api\Controller;

use App\Branch\Api\Middleware\SantizeFormData;
use App\Branch\Api\Middleware\BranchValidation;
use App\Branch\Api\Repository\BranchSaveRepo;
use Az\Route\Route;

#[Route(methods: 'post')]
#[SantizeFormData]
// #[BranchValidation]
class BranchSave extends ApiContractController
{
    public function __invoke(BranchSaveRepo $repo)
    {
        $post = $this->request->getParsedBody();
        $files = $this->request->getUploadedFiles();
        $id = $repo->save($post, $files, $this->user->id);
        return $id;
    }
}
