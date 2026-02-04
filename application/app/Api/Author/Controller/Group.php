<?php

declare(strict_types=1);

namespace App\Api\Author\Controller;

use App\Api\Author\Model\ModelGroup;
use App\Api\Author\Middleware\StatusValidation;
use App\Api\Common\Controller\ApiContractController;
use Sys\Middleware\PreparePostData;
use Az\Route\Route;

class Group extends ApiContractController
{
    public function __construct(private ModelGroup $model) {}

    public function members($id = null)
    {
        return $id ? $this->model->getMembers($id) : [];
    }

    public function getstatus($id)
    {
        $author_id = $this->request->getQueryParams()['author'];
        $status = $this->model->getStatus((int) $id, (int) $author_id);

        return ['status' => $status];
    }

    #[Route(methods: 'post')]
    #[PreparePostData]
    #[StatusValidation]
    public function setstatus()
    {
        $post = $this->request->getParsedBody();
        $this->model->setStatus($post);

        return ['status' => $post['status']];
    }
}
