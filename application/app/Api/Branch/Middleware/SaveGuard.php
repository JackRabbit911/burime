<?php

declare(strict_types=1);

namespace App\Api\Branch\Middleware;

use App\Api\Branch\Model\BranchPermissions;
use Common\Enum\BranchAuthorPermissions;
use Common\Service\Permissions;
use HttpSoft\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SaveGuard implements MiddlewareInterface
{
    public function __construct(private BranchPermissions $model) {}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if ($request->getMethod() === 'GET' || $request->getMethod() === 'HEAD') {
            return $handler->handle($request);
        }

        $branch = $request->getParsedBody()['branch'] ?? null;

        if (!$branch) {
            return $this->abort('Bad request');
        }

        $user = $request->getAttribute('user');

        if (!$user) {
            return $this->abort();
        }

        $branch_id = $branch['id'] ?? null;
        $owner = $branch['owner'] ?? 0;

        if ($owner === $user->id || !$branch_id) {
            return $handler->handle($request);
        }

        $role = $this->model->getRole($branch_id, $user->id);

        $allow = (new Permissions($role))->isAllow(
            BranchAuthorPermissions::EDIT_STATUS,
            BranchAuthorPermissions::EDIT_BRANCH
        );

        if (!$allow) {
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
