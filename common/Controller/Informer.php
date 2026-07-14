<?php

declare(strict_types=1);

namespace Common\Controller;

use Common\Model\ModelUserStat;
use Common\Middleware\UserAuthorsMiddleware;
use Auth\Middleware\AuthMiddleware;
use Sys\Controller\ApiController;

#[AuthMiddleware]
#[UserAuthorsMiddleware]
class Informer extends ApiController
{
    public function __invoke(ModelUserStat $model)
    {
        $data['badge'] = $this->user
            ? $model->getMsgCount($this->user->ownAuthorsIds)['new']
            : 0;

        return $data;
    }
}
