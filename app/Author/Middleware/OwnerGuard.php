<?php

namespace App\Author\Middleware;

use App\Author\Author;
use App\Author\Model\ModelAuthor;
use Attribute;
use Sys\Exception\ExceptionResponseFactory;
use Sys\Helper\ResponseType;
use Az\Route\Route;
use HttpSoft\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[Attribute]
final class OwnerGuard implements MiddlewareInterface
{
    private ModelAuthor $model;
    private ExceptionResponseFactory $factory;

    public function __construct(ModelAuthor $model, ExceptionResponseFactory $factory)
    {
        $this->model = $model;
        $this->factory = $factory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute('user');

        if (!$user) {
            $query = $request->getQueryParams();
            if (isset($query['redirect'])) {
                return new RedirectResponse(url('home'));
            }

            return $this->factory->createResponse(ResponseType::html, 404);
        }

        $id = $request->getAttribute(Route::class)->getParameters()['id'] ?? '';

        if ($id) {
            $author = $this->model->find($id);
        } else {
            $author = new Author;
            $author->set('owner', $user->id);
        }

        if ($author->owner !== $user->id) {
            $query = $request->getQueryParams();
            if (isset($query['redirect'])) {
                return new RedirectResponse(url('home'));
            }

            return $this->factory->createResponse(ResponseType::html, 404);
        }

        if ($author) {
            $request = $request->withAttribute('author', $author);
        }

        return $handler->handle($request);
    }
}
