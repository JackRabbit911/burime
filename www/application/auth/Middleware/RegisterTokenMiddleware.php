<?php

namespace Auth\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RegisterTokenMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $userdata = $request->getParsedBody();
        $session = $request->getAttribute('session');
        $session->set('userdata', $userdata);
        $session->set('code', bin2hex(random_bytes(16)));

        return $handler->handle($request);
    }
}
