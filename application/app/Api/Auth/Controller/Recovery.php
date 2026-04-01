<?php

declare(strict_types=1);

namespace App\Api\Auth\Controller;

use App\Api\Auth\Model\ModelUser;
use App\Api\Auth\Service\SendEmail;
use App\Api\Auth\Model\ModelConfirm;
use App\Api\Auth\Model\ModelRecovery;
use App\Api\Auth\Middleware\EmailValidation;
use App\Api\Auth\Middleware\PasswordConfirmValidation;
use Az\Route\Route;
use Sys\CSRF\Facade\Csrf;
use Sys\CSRF\Middleware\ApiCsrfMiddleware;

#[Route(methods: 'post')]
class Recovery extends ApiAuthController
{
    #[EmailValidation]
    public function email(
        ModelRecovery $modelRecovery,
        ModelConfirm $modelConfirm,
        SendEmail $mailer
    ) {
        $data = $this->request->getBody()->getContents();
        $data = json_decode($data);

        $user = $modelRecovery->findByEmail($data->email);

        $code = $modelConfirm->set();
        $mailer->recovery($this->request->getUri(), $user, $this->i18n->lang(), $code);

        return $user->name;
    }

    #[ApiCsrfMiddleware]
    #[PasswordConfirmValidation]
    public function savepswd(ModelUser $model)
    {
        $data = $this->request->getBody()->getContents();
        $data= json_decode($data);
        $hash = password_hash($data->password, PASSWORD_DEFAULT);
        $model->update(['password' => $hash], $data->id);

        return true;
    }

    #[Route(methods: 'get')]
    public function confirm(ModelConfirm $model, int $id, string $code)
    {
        return $model->get($code) ? Csrf::generate($id, 'password', 7200) : false;
    }
}
