<?php declare(strict_types=1);

namespace Auth\Middleware;

use Attribute;
use HttpSoft\Response\RedirectResponse;
use Sys\Exception\ExceptionResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sys\Helper\ResponseType;

#[Attribute]
class AuthGuardMiddleware implements MiddlewareInterface
{
    private ExceptionResponseFactory $factory;

    public function __construct(ExceptionResponseFactory $factory)
    {
        $this->factory = $factory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$request->getAttribute('user')) {
            $query = $request->getQueryParams();
            if (isset($query['redirect'])) {
                return new RedirectResponse(url('home'));
            }

            return $this->factory->createResponse(ResponseType::html, 404);
        }

        return $handler->handle($request);
    }
}
