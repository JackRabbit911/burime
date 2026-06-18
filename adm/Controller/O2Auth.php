<?php

declare(strict_types=1);

namespace Adm\Controller;

use App\Api\Common\Controller\ApiContractController;
use Adm\Middleware\AuthValidation;
use Adm\Model\ModelAuth;
use Adm\Repository\TokensRepo;
use Az\Route\Route;
use HttpSoft\Response\JsonResponse;

class O2Auth extends ApiContractController
{
    #[Route(methods: 'post')]
    #[AuthValidation]
    public function login(TokensRepo $tokens)
    {
        $user = $tokens->getUser();
        $tokens = $tokens->getTokens();

        $data = [
            'success' => true,
            'result' => ['user' => $user]
        ];

        return new JsonResponse($data, 201, $tokens);
    }

    #[Route(methods: 'delete')]
    public function logout()
    {
        return ['logout' => true];
    }
}
