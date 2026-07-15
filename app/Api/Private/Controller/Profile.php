<?php

declare(strict_types=1);

namespace App\Api\Private\Controller;

use App\Api\Private\Model\ModelUser;
use App\Api\Private\Middleware\ProfileValidation;
use App\Api\Private\Middleware\PasswordConfirmValidation;
use App\Api\Common\Controller\ApiContractController;
use App\Api\Auth\Service\OAuth;
use App\Api\Common\Repository\Avatar;
use Auth\Api\Repository\AuthRepo;
use Sys\CSRF\Middleware\ApiCsrfMiddleware;
use Sys\Middleware\PreparePostData;
use Sys\CSRF\Facade\Csrf;
use Az\Route\Route;

class Profile extends ApiContractController
{
    public function __construct(private ModelUser $model) {}

    public function __invoke()
    {
        $userdata = $this->model->find($this->user->id);
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
    public function save(Avatar $avatar, AuthRepo $repo)
    {
        $file = $this->request->getUploadedFiles()['file'] ?? null;

        $this->model->update($this->data, $this->user->id);
        $avatar->save($file, $this->user->id, Avatar::USER);
        $this->model->update($this->data, $this->user->id);

        $bearer = $repo->encodeJWT($this->user);
        $options = config('o2auth', 'cookie');
        setcookie('OAT', $bearer, $options);

        return ['user' => $this->user];
    }

    #[Route(methods: 'post')]
    #[ApiCsrfMiddleware]
    #[PasswordConfirmValidation]
    public function savepswd()
    {
        $data = $this->request->getParsedBody();
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->model->update(['password' => $hash], $this->user->id);

        return ['id' => $this->user->id];
    }
}
