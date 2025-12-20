<?php

declare(strict_types=1);

namespace Auth\Middleware;

use HttpSoft\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthGuardRedirect implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        if (!$request->getAttribute('user')) {
            return new RedirectResponse(url('home'));
        }

        return $handler->handle($request);
    }
}
