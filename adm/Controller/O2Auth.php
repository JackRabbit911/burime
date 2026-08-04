<?php

declare(strict_types=1);

namespace Adm\Controller;

use Adm\Middleware\AuthValidation;
use App\Api\Common\Controller\ApiContractController;
use Auth\Api\Repository\AuthRepo;
use Az\Route\Route;
use HttpSoft\Response\EmptyResponse;
use HttpSoft\Response\JsonResponse;

class O2Auth extends ApiContractController
{
    private $config = [];

    public function __construct(private AuthRepo $repo)
    {
        $this->config = config('o2auth');
    }

    public function auth(): string
    {
        $refresh = $this->request->getCookieParams()['UAT'] ?? false;

        if (!$refresh) {
            return new EmptyResponse(401);
        }

        [$user, $jwt] = $this->repo->auth($refresh);

        if (!$user) {
            return new EmptyResponse(401);
        }

        if (!$user->role) {
            return new EmptyResponse(403);
        }

        return $jwt;
    }

    #[Route(methods: 'delete')]
    public function logout(): string
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

    #[Route(methods: 'post')]
    public function refresh()
    {
        $refresh = $this->request->getCookieParams()['UAT'] ?? null;

        if (!$refresh) {
            return new EmptyResponse(401);
        }

        $result = $this->repo->rotate($refresh);

        if ($result) {
            if (isset($result->lifetime)) {
                $options = $this->config['cookie'];
                $options['expires'] = time() + $result->lifetime;
                setcookie('UAT', $result->token, $options);
            }

            return $result->bearer;
        }

        return new EmptyResponse(403);
    }

    #[Route(methods: 'delete')]
    public function ban()
    {
        // $user_id = $this->data['user_id'];
        // $this->repo->ban($user_id);
    }
}
