<?php declare(strict_types=1);

namespace Auth\Api\Controller;

use Auth\Api\Middleware\AuthValidation;
use Auth\Api\Model\ModelAuth;
use Auth\Api\Model\ModelRefreshToken;
use Auth\Api\Repository\O2AuthRepo;
use Az\Route\Route;
use Sys\Controller\ApiController;
use HttpSoft\Response\EmptyResponse;
use HttpSoft\Response\JsonResponse;

#[Route(methods: API_ALLOW_METHODS)]
class O2Auth extends ApiController
{
    #[Route(methods: 'post')]
    #[AuthValidation]
    public function login(ModelAuth $model, O2AuthRepo $repo)
    {
        $user = $model->get();

        return new JsonResponse([
            'success' => true,
            'Bearer' => $repo->encodeJWT($user),
            'Refresh' => $repo->createRefreshToken($user->id),
            'user' => $user,
        ]);
    }

    #[Route(methods: 'delete')]
    public function logout(ModelRefreshToken $model)
    {
        $data = $this->request->getBody()->getContents();
        $user_agent = $this->request->getServerParams()['HTTP_USER_AGENT'] ?? null;
        $token = json_decode($data)->token;
        $model->delete($token, $user_agent);
        return new EmptyResponse(205);
    }
}
