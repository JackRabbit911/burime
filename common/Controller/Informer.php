<?php

declare(strict_types=1);

namespace Common\Controller;

use Common\Model\ModelUserStat;
use Sys\Controller\ApiController;
// use Sys\Controller\BaseController;
// use Sys\Response\FileResponse;

class Informer extends ApiController
{
    public function __invoke(ModelUserStat $model)
    {
        $data['badge'] = $this->user
            ? $model->getMsgCount($this->user->ownAuthors->props()->all())['new']
            : 0;

        return $data;
    }
}
