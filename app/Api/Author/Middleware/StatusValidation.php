<?php

declare(strict_types=1);

namespace App\Api\Author\Middleware;

use App\Api\Common\Middleware\ApiContractValidation;

class StatusValidation extends ApiContractValidation
{
    protected function setRules($request)
    {
        $this->validation
            ->rule('parent_id', 'required|integer')
            ->rule('child_id', 'required|integer')
            ->rule('role', 'integer')
            ->rule('status', 'required|integer')
        ;
    }
}
