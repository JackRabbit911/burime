<?php

declare(strict_types=1);

namespace Adm\Middleware;

use HttpSoft\Response\EmptyResponse;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class AdmGuard implements MiddlewareInterface
{
    private int $role;

    public function __construct(int $role)
    {
        $this->role = $role;
    }

    public function process(Request $request, Handler $handler): Response
    {
        $user = $request->getAttribute('user');

        if (($user?->role & $this->role) !== $this->role) {
            return new EmptyResponse(403);
        }

        return $handler->handle($request);
    }
}
