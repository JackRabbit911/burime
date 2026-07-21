<?php

declare(strict_types=1);

namespace Adm\Controller;

use Adm\Model\ModelUsers;
use Adm\Middleware\SearchValidation;
use App\Api\Common\Controller\ApiContractController;
use Auth\Component\Avatar;
use Auth\Api\Middleware\O2AuthGuard;
use Sys\Controller\ApiController;

// #[O2AuthGuard]
// #[SearchValidation]
class Users extends ApiContractController
{
    public function __construct(private ModelUsers $model) {}

    public function __invoke(?int $id)
    {
        return ($id) ? $this->user($id) : $this->list();
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
