<?php

declare(strict_types=1);

namespace Auth\Api\Controller;

use Auth\Api\Model\ModelUser;
use Auth\Api\Service\SendEmail;
use Auth\Api\Model\ModelConfirm;
use Auth\Api\Model\ModelRecovery;
use Auth\Api\Middleware\EmailValidation;
use Auth\Api\Middleware\PasswordConfirmValidation;
use Az\Route\Route;
use Sys\CSRF\Facade\Csrf;
use Sys\CSRF\Middleware\ApiCsrfMiddleware;

#[Route(methods: 'post')]
class Recovery extends ApiAuthController
{
    public function __construct(private ModelConfirm $confirm) {}

    #[EmailValidation]
    public function email(
        ModelRecovery $modelRecovery,
        SendEmail $mailer
    ) {
        $data = $this->request->getBody()->getContents();
        $data = json_decode($data);

        $user = $modelRecovery->findByEmail($data->email);

        $code = $this->confirm->set($user);
        $mailer->recovery($this->request->getUri(), $user, $this->i18n->lang(), $code);

        return $user->name;
    }

    #[PasswordConfirmValidation]
    public function savepswd(ModelUser $model)
    {
        $data = $this->request->getBody()->getContents();
        $data = json_decode($data);

        if (($result = $this->confirm->check($data->code, $data->id))) {
            $hash = password_hash($data->password, PASSWORD_DEFAULT);
            $model->update(['password' => $hash], $data->id);
        }

        return $result;
    }
}
