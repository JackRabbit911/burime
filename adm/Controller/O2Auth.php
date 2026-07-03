<?php

declare(strict_types=1);

namespace Adm\Controller;

use App\Api\Common\Controller\ApiContractController;
use Adm\Middleware\AuthValidation;
use Adm\Repository\AuthRepo;
use Az\Route\Route;
use HttpSoft\Response\EmptyResponse;

class O2Auth extends ApiContractController
{
    public function __construct(private AuthRepo $repo) {}

    #[Route(methods: 'post')]
    #[AuthValidation]
    public function login()
    {
        return $this->repo->login();
    }

    #[Route(methods: 'delete')]
    public function logout()
    {
        $this->repo->logout($this->data['refresh']);
        return 'Goodbye';
    }

    #[Route(methods: 'delete')]
    public function quit()
    {
        $this->repo->logoutGlobal($this->data['refresh']);
        return 'Goodbye';
    }

    #[Route(methods: 'post')]
    public function refresh()
    {
        $data = $this->repo->rotate($this->data['refresh']);
        return (($data)) ?: new EmptyResponse(401);
    }

    #[Route(methods: 'delete')]
    public function ban()
    {
        $user_id = $this->data['user_id'];
        $this->repo->ban($user_id);
    }
}
