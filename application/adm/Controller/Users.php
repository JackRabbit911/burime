<?php

declare(strict_types=1);

namespace Adm\Controller;

use Adm\Model\ModelUsers;
use Adm\Middleware\SearchValidation;
use Sys\Controller\ApiController;

#[SearchValidation]
class Users extends ApiController
{
    public function __construct(private ModelUsers $model){}

    public function __invoke()
    {
        $params = $this->request->getQueryParams();
        $filter = $params['name'] ?? null;
        $page = $params['pageNumber'] ?? 1;
        $limit = $params['perPage'] ?? 10;
        $offset = ((int) $page - 1) * (int) $limit;

        return $this->model->get((int) $limit, $offset, $filter);
    }
}
