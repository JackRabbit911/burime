<?php

declare(strict_types=1);

namespace App\Api\Common\Middleware;

use HttpSoft\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthGuard implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        if (!$request->getAttribute('user')) {
            return new EmptyResponse(401);
        }

        return $handler->handle($request);
    }
}
