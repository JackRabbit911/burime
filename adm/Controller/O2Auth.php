<?php

declare(strict_types=1);

namespace Adm\Controller;

use Adm\Middleware\AuthValidation;
use App\Api\Common\Controller\ApiContractController;
use Auth\Api\Repository\AuthRepo;
use Az\Route\Route;
use HttpSoft\Response\EmptyResponse;

class O2Auth extends ApiContractController
{
    private $config = [];

    public function __construct(private AuthRepo $repo)
    {
        $this->config = config('o2auth');
    }

    public function auth()
    {
        $refresh = $this->request->getCookieParams()['UAT'] ?? false;
        return $refresh ? $this->repo->auth($refresh) : new EmptyResponse(401);
    }

    #[Route(methods: 'delete')]
    public function logout()
    {
        $token = $this->request->getCookieParams()['UAT'] ?? null;

        if (!$token) {
            return 'Goodbye';
        }

        $this->repo->logout($token);

        $options = $this->config['cookie'];
        $options['expires'] = time() - 3600;

        setcookie('OAT', '', $options);
        setcookie('UAT', '', $options);

        return 'Goodbye';
    }

    #[Route(methods: 'delete')]
    public function quit()
    {
        $token = $this->request->getCookieParams()['UAT'] ?? null;

        if (!$token) {
            return 'Goodbye';
        }

        $this->repo->logoutGlobal($token);

        $options = $this->config['cookie'];
        $options['expires'] = time() - 3600;
        setcookie('UAT', '', $options);

        return 'Goodbye';
    }

    #[Route(methods: 'post')]
    public function refresh()
    {
        $refresh = $this->request->getCookieParams()['UAT'] ?? null;
        $data = $this->repo->rotate($refresh);

        return (($data)) ?: new EmptyResponse(401);
    }

    #[Route(methods: 'delete')]
    public function ban()
    {
        // $user_id = $this->data['user_id'];
        // $this->repo->ban($user_id);
    }
}
