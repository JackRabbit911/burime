<?php

declare(strict_types=1);

namespace App\Branch\Api\Middleware;

use App\Branch\Api\Repository\BranchRepo;
use Az\Route\Route;
use HttpSoft\Response\EmptyResponse;
use HttpSoft\Response\JsonResponse;
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
        $route = $request->getAttribute(Route::class);
        $id = $route->getParameters()['id'] ?? null;

        if (!$id) {
            return $handler->handle($request);
        }
        
        $branch = $this->repo->findBranch((int)$id);

        if (!$branch) {
            return new EmptyResponse(404);
        }

        if ($branch->owner !== $user->id) {
            return new EmptyResponse(403);
        }

        return $handler->handle($request->withAttribute('branch', $branch));
    }
}
