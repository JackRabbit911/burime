<?php

declare(strict_types=1);

namespace Adm\Controller;

use Adm\Model\ModelUsers;
use Adm\Middleware\SearchValidation;
use Adm\Repository\UserRepo;
use Adm\Middleware\AdmGuard;
use Adm\Service\ADM;
use App\Api\Common\Controller\ApiContractController;
use Auth\Api\Middleware\O2AuthGuard;
use Sys\Controller\ApiController;
use Az\Route\Route;

// #[O2AuthGuard]
// #[SearchValidation]
#[AdmGuard(role: ADM::USERS)]
class Users extends ApiContractController
{
    public function __construct(
        private ModelUsers $model,
        private UserRepo $userRepo,
    ) {}

    public function __invoke(?int $id)
    {
        return ($id) ? $this->user($id) : $this->list();
    }

    #[Route(methods: 'post')]
    public function save(int $id)
    {
        $this->model->setAdmRole($this->data);
        return 'Ok';
    }

    private function list()
    {
        $params = $this->request->getQueryParams();
        $filter = $params['filter'] ?? null;
        $search = $params['search'] ?? null;
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $offset = ((int) $page - 1) * (int) $limit;

        return $this->model->get((int) $limit, $offset, $filter, $search);
    }

    private function user(int $id)
    {
        return $this->userRepo->getUser($this->user->role, $id);
    }
}
