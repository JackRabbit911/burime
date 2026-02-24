<?php

declare(strict_types=1);

namespace App\Api\Auth\Controller;

use App\Api\Auth\Model\ModelUser;
use App\Api\Auth\Service\SendEmail;
use App\Api\Auth\Model\ModelConfirm;
use App\Api\Auth\Middleware\RegisterValidation;
use Az\Route\Route;
use stdClass;

class Register extends ApiAuthController
{
    public function __construct(private ModelConfirm $model){}
    
    #[Route(methods: 'post')]
    #[RegisterValidation]
    public function save(SendEmail $mailer)
    {
        $data = $this->request->getBody()->getContents();
        $data= json_decode($data);

        $user = new stdClass;
        $user->name = $data->name;
        $user->email = $data->email;
        $user->password = password_hash($data->password, PASSWORD_DEFAULT);

        $code = $this->model->set($user);        
        $mailer->register($this->request->getUri(), $user, $this->i18n->lang(), $code);

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
