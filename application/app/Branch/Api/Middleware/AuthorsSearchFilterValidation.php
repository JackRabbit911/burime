<?php

declare(strict_types=1);

namespace App\Branch\Api\Middleware;

use Az\Validation\Middleware\ApiValidationMiddleware;
use Common\Enum\MemberRole;
use HttpSoft\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class AuthorsSearchFilterValidation extends ApiValidationMiddleware
{
    protected function setRules($request)
    {
        $whitelist = MemberRole::getFilters();
        $this->validation->rule('filter', 'inArray', $whitelist)
            ->rule('search', 'text_utf8')
            ->rule('page', 'integer')
            ->rule('limit', 'integer');
    }

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
                'error' => $this->validation->getResponse(true)
            ]);
    }
}
