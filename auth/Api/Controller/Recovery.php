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

#[Route(methods: 'post')]
class Recovery extends ApiAuthController
{
    public function __construct(private ModelConfirm $confirm) {}

    #[EmailValidation]
    public function email(
        ModelRecovery $modelRecovery,
        SendEmail $mailer
    ) {
        $user = $modelRecovery->findByEmail($this->data->email);

        $code = $this->confirm->set($user);

        if (ENV !== TESTING) {
            $mailer->recovery($this->request->getUri(), $user, $this->i18n->lang(), $code);
        }

        $data = [
            'name' => $user->name,
        ];

        if (ENV === TESTING) {
            $data['code'] = "$user->id/$code";
        }

        return $data;
    }

    #[PasswordConfirmValidation]
    public function savepswd(ModelUser $model)
    {
        if (($result = $this->confirm->check($this->data->code, $this->data->id))) {
            $hash = password_hash($this->data->password, PASSWORD_DEFAULT);
            $model->update(['password' => $hash], $this->data->id);
        }

        return $result;
    }
}
