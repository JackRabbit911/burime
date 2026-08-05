<?php

declare(strict_types=1);

namespace Adm\Middleware;

use App\Api\Common\Middleware\ApiContractValidation;

class SearchValidation extends ApiContractValidation
{
    protected function setRules($request)
    {
        $this->validation->rule('search', 'username');
    }
}
