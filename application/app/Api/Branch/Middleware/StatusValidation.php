<?php

declare(strict_types=1);

namespace App\Api\Branch\Middleware;

use App\Api\Common\Middleware\ApiContractValidation;

class StatusValidation extends ApiContractValidation
{
    protected function setRules($request)
    {
        $this->validation
            ->rule('branch_id', 'required|integer')
            ->rule('author_id', 'required|integer')
            ->rule('role', 'integer')
            ->rule('status', 'required|integer')
        ;
    }
}
