<?php declare(strict_types=1);

namespace App\Branch\Middleware;

use Sys\Request\External\Wrapper;
use Az\Route\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Attribute;

#[Attribute]
final class OwnerBranchGuard implements MiddlewareInterface
{
    public function __construct(private Wrapper $client){}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute('user');
        $route = $request->getAttribute(Route::class);
        $id = $route->getParameters()['id'] ?? null;

        $path = path('int.savepost', ['action' => 'getbranch', 'id' => $id]);
        $branch = $this->client->get($path);
        $branch = unserialize($branch);

        if (!$branch || !$user || isset($branch->owner) && $user->id !== $branch->owner) {
            abort();
        }

        return $handler->handle($request->withAttribute('branch', $branch));
    }
}
