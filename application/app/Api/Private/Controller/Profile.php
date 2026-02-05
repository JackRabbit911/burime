<?php

declare(strict_types=1);

namespace App\Api\Private\Controller;

use App\Api\Private\Model\ModelUser;
use App\Api\Private\Middleware\ProfileValidation;
use App\Api\Private\Repository\UserAvatarSaveRepo;
use App\Api\Private\Middleware\PasswordConfirmValidation;
use App\Api\Common\Controller\ApiContractController;
use Sys\Middleware\PreparePostData;
use Az\Route\Route;

class Profile extends ApiContractController
{
    public function __invoke(ModelUser $model)
    {
        return $model->find($this->user->id);
    }

    #[Route(methods: 'post')]
    #[PreparePostData]
    #[ProfileValidation]
    public function save(UserAvatarSaveRepo $repo)
    {
        $data = $this->request->getParsedBody();
        $file = $this->request->getUploadedFiles()['file'] ?? null;

        $this->user->update($data)->save();
        $repo->saveFile($file, $this->user->id);

        return ['id' => $this->user->id];
    }

    #[Route(methods: 'post')]
    #[PasswordConfirmValidation]
    public function savepswd(ModelUser $model)
    {
        $data = $this->request->getParsedBody();
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $model->update($hash, $this->user->id);

        return ['id' => $this->user->id];
    }
}
