<?php

declare(strict_types=1);

namespace Adm\Controller;

use Adm\Model\ModelUsers;
use Adm\Middleware\SearchValidation;
use Auth\Component\Avatar;
use Sys\Controller\ApiController;

#[SearchValidation]
class Users extends ApiController
{
    public function __construct(private ModelUsers $model){}

    public function __invoke(?int $id)
    {
        return [
            'success' => true,
            'result' =>  $id ? $this->user($id) : $this->list(),
        ];
    }

    private function list()
    {
        $params = $this->request->getQueryParams();
        $filter = $params['name'] ?? null;
        $page = $params['pageNumber'] ?? 1;
        $limit = $params['perPage'] ?? 10;
        $offset = ((int) $page - 1) * (int) $limit;

        return $this->model->get((int) $limit, $offset, $filter);
    }

    private function user(int $id)
    {
        $user = $this->model->read($id);
        $user->avatar = Avatar::getSrc($id);

        return $user;
    }
}
