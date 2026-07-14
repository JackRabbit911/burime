<?php

declare(strict_types=1);

namespace Common\Middleware;

use Common\Model\ModelUser;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class UserAuthorsMiddleware implements MiddlewareInterface
{
    public function __construct(private ModelUser $model) {}

    public function process(Request $request, Handler $handler): Response
    {
        $user = $request->getAttribute('user');
        
        if (!$user) {
            return $handler->handle($request);
        }

        $user->ownAuthorsIds = $this->model->getOwnAuthorsIds($user->id);
        
        return $handler->handle($request->withAttribute('user', $user));
    }
}
