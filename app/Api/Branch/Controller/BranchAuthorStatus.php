<?php

declare(strict_types=1);

namespace App\Api\Branch\Controller;

use App\Api\Branch\Model\ModelStatus;
use App\Api\Branch\Middleware\StatusValidation;
use App\Api\Common\Controller\ApiContractController;
use Sys\Middleware\PreparePostData;
use Az\Route\Route;

class BranchAuthorStatus extends ApiContractController
{
    public function __construct(private ModelStatus $model) {}

    public function get($id)
    {
        $author_id = $this->request->getQueryParams()['author'];
        $status = $this->model->getStatus((int) $id, (int) $author_id);

        return ['status' => $status];
    }

    #[Route(methods: 'post')]
    #[PreparePostData]
    #[StatusValidation]
    public function set()
    {
        $post = $this->request->getParsedBody();
        $this->model->setStatus($post);

        return ['status' => $post['status']];
    }
}
