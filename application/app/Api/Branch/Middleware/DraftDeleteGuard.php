<?php

declare(strict_types=1);

namespace App\Api\Branch\Middleware;

use App\Api\Branch\Model\ModelDraft;
use Az\Route\Route;
use HttpSoft\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DraftDeleteGuard implements MiddlewareInterface
{
    public function __construct(private ModelDraft $model) {}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $user = $request->getAttribute('user');

        if (!$user) {
            return $this->abort();
        }

        $route = $request->getAttribute(Route::class);
        $draft_id = $route->getParameters()['id'] ?? null;

        $owner = $this->model->getOwner((int) $draft_id);

        if ($owner !== $user->id) {
            return $this->abort();
        }

        return $handler->handle($request);
    }

    private function abort(string | array $message = 'Not enough permissions')
    {
        return new JsonResponse([
            'success' => false,
            'error' => $message,
        ]);
    }
}
