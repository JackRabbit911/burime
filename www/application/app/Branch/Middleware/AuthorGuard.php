<?php declare(strict_types=1);

namespace App\Branch\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Attribute;

#[Attribute]
final class AuthorGuard implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute('user');
        
        if (!$user) {
            redirect(path('home'));
        }

        if ($user->ownAuthors->empty()) {
            redirect(path('author.form'));
        }

        return $handler->handle($request);
    }
}
