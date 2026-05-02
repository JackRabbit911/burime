<?php

declare(strict_types=1);

namespace App\Api\Author\Controller;

use App\Api\Author\Repository\AuthorDeleteRepo;
use App\Api\Author\Repository\AuthorSaveRepo;
use App\Api\Author\Middleware\AuthorValidation;
use App\Api\Common\Controller\ApiContractController;
use App\Api\Common\Repository\InviteMessageRepo;
use Sys\CSRF\Middleware\ApiCsrfMiddleware;
use Sys\Middleware\PreparePostData;
use Az\Route\Route;

class AuthorSave extends ApiContractController
{
    #[Route(methods: 'post')]
    #[ApiCsrfMiddleware]
    #[PreparePostData]
    #[AuthorValidation]
    public function save(AuthorSaveRepo $repo, InviteMessageRepo $invite)
    {
        $post = $this->request->getParsedBody();
        $file = $this->request->getUploadedFiles()['file'] ?? null;

        $id = $repo->savePost($post, $this->user->id);
        $repo->saveFile($file, $id);

        $invite->sendInviteToGroup($post, $id);

        return ['id' => $id];
    }

    #[Route(methods: 'delete')]
    public function delete(AuthorDeleteRepo $repo, int $id)
    {
        return $repo->deleteAuthor($id);
    }
}
