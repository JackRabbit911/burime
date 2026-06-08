<?php

declare(strict_types=1);

namespace App\Home\Middleware;

use Az\Validation\Middleware\ValidationMiddleware;
use Psr\Http\Message\ServerRequestInterface;

class AuthorsFilterValidation extends ValidationMiddleware
{
    protected function setRules(ServerRequestInterface $request)
    {
        $this->validation
            // ->rule('filter', 'alpha')
            ->rule('search', 'text_utf8')
            ->rule('show', 'inList(cards, table, list)')
            ->rule('page', 'integer')
            ->rule('limit', 'integer');
    }
}
