<?php declare(strict_types = 1);

namespace Auth\Middleware;

use Attribute;
use HttpSoft\Response\EmptyResponse;
use HttpSoft\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[Attribute]
class ApiAuthGuard implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
        ): ResponseInterface
        {
            if (!$request->getAttribute('user')) {
                return new JsonResponse('Unauthorized', 401);
            }

            return $handler->handle($request);
        }
}
