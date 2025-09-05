<?php

declare(strict_types=1);

namespace App\Branch\Api\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sys\Helper\Form;

class SantizeFormData implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        $post = $request->getParsedBody();
        Form::santizeFormData($post, ['title' => 'string']);
        return $handler->handle($request->withParsedBody($post));
    }
}
