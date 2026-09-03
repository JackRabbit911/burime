<?php

declare(strict_types=1);

namespace App\Home\Middleware;

use App\Api\Common\Middleware\ApiContractValidation;
use Psr\Http\Message\ServerRequestInterface;

class BooksFilterValidation extends ApiContractValidation
{
    protected function setRules(ServerRequestInterface $request)
    {
        $this->validation->rule('filter', 'integer')
            ->rule('search', 'text_utf8')
            ->rule('show', 'inList(cards, table, list)')
            ->rule('page', 'integer')
            ->rule('limit', 'integer');
    }
}
