<?php declare(strict_types=1);

namespace App\Branch\Middleware;

use Attribute;
use Common\Repository\BranchRepo;
use Az\Route\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[Attribute]
final class OwnerBranchGuard implements MiddlewareInterface
{
    private BranchRepo $repo;

    public function __construct(BranchRepo $repo)
    {
        $this->repo = $repo;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute('user');
        $route = $request->getAttribute(Route::class);
        $id = $route->getParameters()['id'] ?? null;

        $branch = $this->repo->find($id);

        if (isset($branch->owner) && $user->id !== $branch->owner) {
            abort();
        }

        return $handler->handle($request->withAttribute('branch', $branch));
    }
}
