<?php

declare(strict_types=1);

namespace Adm\Middleware;

use Az\Validation\Middleware\ApiValidationMiddleware;

class SearchValidation extends ApiValidationMiddleware
{
    protected function setRules($request)
    {
        $this->validation->rule('name', 'username');
    }
}
