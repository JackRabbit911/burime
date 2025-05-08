<?php declare(strict_types=1);

namespace Api\Controller;

use Auth\Api\Middleware\AuthValidation;
use Auth\Api\Service\OAuthService;
use Auth\Middleware\ApiAuthGuard;
use Auth\Model\ModelUser;
use Auth\User;
use Az\Route\Route;
use stdClass;
use Sys\Controller\ApiController;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Sys\Response\ResponseHeader;
use Throwable;

// #[ApiAuthGuard]
#[Route(methods: API_ALLOW_METHODS)]
class ApiTest extends ApiController
{
    public function __construct(private ModelUser $modelUser){}

    #[AuthValidation]
    public function login(OAuthService $oauth)
    {
        $user = $this->modelUser->getUser();
        $userDto = $oauth->getUserDto($user);

        $accessToken = $oauth->getAccessToken($userDto);
        $refreshToken = $oauth->getRefreshToken($user->id);

        return [
            'status' => 'success',
            'user' => $userDto,
            'bearer' => $accessToken,
            'refresh' => $refreshToken,
        ];
    }

    public function jwt(OAuthService $oauth)
    {
        $userDto = new stdClass;
        $userDto->id = 1;
        $userDto->name = 'Alexey';

        $accessToken = $oauth->getAccessToken($userDto);
        $refreshToken = $oauth->getRefreshToken($userDto->id);

        return [
            'status' => 'success',
            'user' => $userDto,
            'bearer' => $accessToken,
            'refresh' => $refreshToken,
        ];
    }


    #[ApiAuthGuard]
    public function __invoke()
    {
        return ['user' => $this->user];
    }

    #[ApiAuthGuard]
    public function secret()
    {
        return ['foo' => 'Сверхсекрет'];
    }    
}
