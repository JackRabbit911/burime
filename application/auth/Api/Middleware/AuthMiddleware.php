<?php

declare(strict_types=1);

namespace Auth\Api\Middleware;

use Auth\Api\Service\OAuthService;
use HttpSoft\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sys\Middleware\CORSMiddleware;
use Sys\Response\ResponseHeader;

final class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private OAuthService $oauth,
        private CORSMiddleware $cors
    ){}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $request->getHeaderLine('Authorization');

        if ($token) {
            if (str_starts_with($token, 'Bearer')) {
                $token = str_replace('Bearer ', '', $token);
                $user = $this->oauth->authByJwt($token);

                if ($user) {
                    $request = $request->withAttribute('user', $user);
                } else {
                    return new JsonResponse([], 200, ['X-Token' => 'Refresh']);
                }
            } 
            elseif (str_starts_with($token, 'Refresh')) {
                $token = str_replace('Refresh ', '', $token);
                $user = $this->oauth->getUserByRefresh($token);

                if ($user) {
                    $userDto = $this->oauth->getUserDto($user);
                    $bearer = $this->oauth->getAccessToken($userDto);
                    $request = $request->withAttribute('user', $user);
    
                    ResponseHeader::addHeader('Bearer', $bearer);
                } 
            }
        }
        
        return $handler->handle($request);
    }
}
