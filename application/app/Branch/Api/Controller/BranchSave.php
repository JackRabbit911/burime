<?php

declare(strict_types=1);

namespace App\Branch\Api\Controller;

use App\Branch\Api\BranchDTO;
use App\Branch\Api\Middleware\SantizeFormData;
use App\Branch\Api\Middleware\BranchValidation;
use Az\Route\Route;

#[Route(methods: 'post')]
#[SantizeFormData]
#[BranchValidation]
class BranchSave extends ApiContractController
{
    public function __invoke()
    {
        $post = $this->request->getParsedBody();
        return new BranchDTO($post);
    }
}
