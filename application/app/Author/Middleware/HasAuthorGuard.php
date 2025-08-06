<?php

declare(strict_types=1);

namespace App\Author\Middleware;

use App\Message\Model\HasAuthor;
use HttpSoft\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HasAuthorGuard implements MiddlewareInterface
{
    public function __construct(
        private HasAuthor $model,
    ){}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        $user = $request->getAttribute('user');
        $hasOwnAuthor = $this->model->has($user->id);

        if ($hasOwnAuthor) {
            return $handler->handle($request);
        }

        return new RedirectResponse(path('author.guard'));
    }
}
