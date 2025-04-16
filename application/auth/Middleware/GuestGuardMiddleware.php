<?php

namespace Auth\Middleware;

use Attribute;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[Attribute]
final class GuestGuardMiddleware implements MiddlewareInterface
{
    private RequestHandlerInterface $defaultHandler;

    public function __construct(RequestHandlerInterface $handler)
    {
        $this->defaultHandler = $handler;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getAttribute('user')) {
            return $this->defaultHandler->handle($request);
        }

        return $handler->handle($request);
    }
}
