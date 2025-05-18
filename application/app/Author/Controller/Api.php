<?php

declare(strict_types=1);

namespace App\Author\Controller;

use App\Author\Model\ModelAuthor;
use Sys\Controller\BaseController;
use HttpSoft\Response\TextResponse;

class Api extends BaseController
{
    public function getalias(ModelAuthor $model, $id)
    {
        $alias = $model->getAlias($id);
        return new TextResponse($alias);
    }
}
