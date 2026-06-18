<?php

declare(strict_types=1);

namespace App\Api\Common\Middleware;

use Az\Validation\Middleware\ApiValidationMiddleware;
use HttpSoft\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

abstract class ApiContractValidation extends ApiValidationMiddleware
{
    public function getResponse(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
        bool $check,
    ): ResponseInterface
    {
        return $check
            ? $handler->handle($request)
            : new JsonResponse([
                    'success' => false,
                    'error' => $this->validation->getResponse(true),
                ], $this->validation->statusCode());
    }
}
