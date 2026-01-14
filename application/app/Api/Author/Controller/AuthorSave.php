<?php

declare(strict_types=1);

namespace App\Api\Author\Controller;

use App\Api\Author\Repository\AuthorDeleteRepo;
use App\Api\Author\Repository\AuthorSaveRepo;
use App\Api\Common\Controller\ApiContractController;
use Az\Route\Route;

class AuthorSave extends ApiContractController
{
    #[Route(methods: 'post')]
    public function save(AuthorSaveRepo $repo)
    {
        $post = $this->request->getParsedBody();
        $file = $this->request->getUploadedFiles()['file'] ?? null;

        $id = $repo->savePost($post, $this->user->id);
        $repo->saveFile($file, $id);

        return ['id' => $id];
    }

    #[Route(methods: 'delete')]
    public function delete(AuthorDeleteRepo $repo, int $id)
    {
        return $repo->deleteAuthor($id);
    }
}
