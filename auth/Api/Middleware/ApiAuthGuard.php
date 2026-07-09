<?php

declare(strict_types=1);

namespace Auth\Api\Middleware;

use HttpSoft\Response\EmptyResponse;
use HttpSoft\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;


class ApiAuthGuard implements MiddlewareInterface
{
    public function process(Request $request, Handler $handler): Response
    {
        $user = $request->getAttribute('user');

        if (!$user) {
            return new EmptyResponse(401);
        }

        return $handler->handle($request);
    }
}

