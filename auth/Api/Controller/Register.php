<?php

declare(strict_types=1);

namespace Auth\Api\Controller;

use Auth\Api\Model\ModelUser;
use Auth\Api\Service\SendEmail;
use Auth\Api\Model\ModelConfirm;
use Auth\Api\Middleware\RegisterValidation;
use Az\Route\Route;
use stdClass;

class Register extends ApiAuthController
{
    public function __construct(private ModelConfirm $model) {}

    #[Route(methods: 'post')]
    #[RegisterValidation]
    public function save(SendEmail $mailer)
    {
        $data = $this->request->getBody()->getContents();
        $data = json_decode($data);

        $user = new stdClass;
        $user->name = $data->name;
        $user->email = $data->email;
        $user->password = password_hash($data->password, PASSWORD_DEFAULT);

        $code = $this->model->set($user);

        if (ENV !== TESTING) {
            $mailer->register($this->request->getUri(), $user, $this->i18n->lang(), $code);
        }

        $data = [
            'name' => $user->name,
        ];

        if (ENV === TESTING) {
            $data['code'] = $code;
        }

        return $data;
    }

    public function confirm(ModelUser $model, string $code)
    {
        $data = $this->model->get($code);

        if (!empty($data)) {
            $user = json_decode($data->user, true);
            $model->create($user);
            return true;
        }

        return false;
    }
}
