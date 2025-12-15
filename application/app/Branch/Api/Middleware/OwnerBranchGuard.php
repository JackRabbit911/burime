<?php

declare(strict_types=1);

namespace App\Branch\Api\Middleware;

use App\Branch\Api\Repository\BranchRepo;
use HttpSoft\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class OwnerBranchGuard implements MiddlewareInterface
{
    public function __construct(private BranchRepo $repo){}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        $user = $request->getAttribute('user');
        $data = $request->getAttribute('branch');

        if ($data['branch']->owner && $data['branch']->owner !== $user->id) {
            return new EmptyResponse(403);
        }

        return $handler->handle($request);
    }
}
