<?php declare(strict_types=1);

namespace App\Author\Middleware;

use App\Author\Model\ModelAuthor;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UserAuthorsMiddleware implements MiddlewareInterface
{
    private ModelAuthor $modelAuthor;

    public function __construct(ModelAuthor $model)
    {
        $this->modelAuthor = $model;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute('user');

        if ($user) {
            $user->ownAuthors = $this->modelAuthor->getByUser($user->id);
        }
        
        return $handler->handle($request);
    }
}
