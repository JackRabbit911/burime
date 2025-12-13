<?php

declare(strict_types=1);

namespace App\Branch\Api\Middleware;

use App\Branch\Api\Repository\BranchRepo;
use App\Branch\Api\Repository\DraftRepo;
use Az\Route\Route;
use HttpSoft\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DraftBranchGetter implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        $route = $request->getAttribute(Route::class);
        $id = $route->getParameters()['id'] ?? null;
        $draft = $route->getParameters()['draft'] ?? null;

        $repo_class_name = $draft ? DraftRepo::class : BranchRepo::class;
        $repo = container()->get($repo_class_name);

        $data = $repo->get($id);

        if (!$data) {
            return new EmptyResponse(404);
        }

        return $handler->handle($request
            ->withAttribute('branch', $data)
            ->withAttribute('repo', $repo)
        );
    }
}
