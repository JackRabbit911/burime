<?php

declare(strict_types=1);

namespace App\Api\Private\Controller;

use App\Api\Common\Controller\ApiContractController;
use App\Api\Private\Middleware\ProfileValidation;
use App\Api\Private\Repository\UserAvatarSaveRepo;
use Sys\Middleware\PreparePostData;
use Az\Route\Route;

class Profile extends ApiContractController
{
    public function __invoke()
    {
        return $this->user;
    }

    #[Route(methods: 'post')]
    #[PreparePostData]
    #[ProfileValidation]
    public function save(UserAvatarSaveRepo $repo)
    {
        $post = $this->request->getParsedBody();
        $file = $this->request->getUploadedFiles()['file'] ?? null;

        $this->user->update($post)->save();
        $repo->saveFile($file, $this->user->id);

        return ['id' => $this->user->id];
    }
}
