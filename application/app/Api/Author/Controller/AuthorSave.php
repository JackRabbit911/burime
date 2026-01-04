<?php

declare(strict_types=1);

namespace App\Api\Author\Controller;

use App\Api\Author\Repository\AuthorSaveRepo;
use App\Api\Common\Controller\ApiContractController;
use Az\Route\Route;

#[Route(methods: 'post')]
class AuthorSave extends ApiContractController
{
    public function __construct(private AuthorSaveRepo $repo) {}

    public function save()
    {
        $post = $this->request->getParsedBody();
        $file = $this->request->getUploadedFiles()['file'] ?? null;

        $id = $this->repo->savePost($post, $this->user->id);
        $this->repo->saveFile($file, $this->user->id);

        return ['id' => $id];
    }
}
