<?php declare(strict_types = 1);

namespace Auth\Middleware;

use Attribute;
use HttpSoft\Response\EmptyResponse;
use HttpSoft\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sys\Middleware\CORSMiddleware;

#[Attribute]
class ApiAuthGuard implements MiddlewareInterface
{
    public function __construct(private CORSMiddleware $cors){}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
        ): ResponseInterface
        {
            if (!$request->getAttribute('user')) {
                $contract = config('api_contracts', $request->getUri()->getPath());
                $headers = $this->cors->getHeaders($request, $contract);
                return new EmptyResponse(401, $headers);
            }

            return $handler->handle($request);
        }
}
