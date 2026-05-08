<?php

declare(strict_types=1);

namespace App\Api\Private\Controller;

use App\Api\Private\Model\ModelUser;
use App\Api\Private\Middleware\ProfileValidation;
use App\Api\Private\Middleware\PasswordConfirmValidation;
use App\Api\Common\Controller\ApiContractController;
use App\Api\Auth\Service\OAuth;
use App\Api\Common\Repository\Avatar;
use Sys\CSRF\Middleware\ApiCsrfMiddleware;
use Sys\CSRF\Middleware\ApiDeleteCsrf;
use Sys\Middleware\PreparePostData;
use Sys\CSRF\Facade\Csrf;
use Az\Route\Route;

class Profile extends ApiContractController
{
    public function __construct(private ModelUser $model) {}

    public function __invoke()
    {
        $userdata = $this->model->find($this->user->id);
        Csrf::send($this->user->id, 7200);

        return $userdata;
    }

    public function csrf()
    {
        return Csrf::generate($this->user->id, 7200);
    }

    #[Route(methods: 'post')]
    #[ApiCsrfMiddleware]
    #[PreparePostData]
    #[ProfileValidation]
    #[ApiDeleteCsrf]
    public function save(Avatar $avatar, OAuth $auth)
    {
        $data = $this->request->getParsedBody();
        $file = $this->request->getUploadedFiles()['file'] ?? null;

        $this->model->update($data, $this->user->id);
        $avatar->save($file, $this->user->id, Avatar::USER);
        $auth->login($this->user->update($data));

        return ['id' => $this->user->id];
    }

    #[Route(methods: 'post')]
    #[ApiCsrfMiddleware]
    #[PasswordConfirmValidation]
    #[ApiDeleteCsrf]
    public function savepswd()
    {
        $data = $this->request->getParsedBody();
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->model->update(['password' => $hash], $this->user->id);

        return ['id' => $this->user->id];
    }
}
