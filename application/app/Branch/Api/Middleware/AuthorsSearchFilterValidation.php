<?php

declare(strict_types=1);

namespace App\Branch\Api\Middleware;

use Common\Enum\MemberRole;

class AuthorsSearchFilterValidation extends ApiContractValidation
{
    protected function setRules($request)
    {
        $whitelist = MemberRole::getFilters();
        $this->validation
            ->rule('filter', 'inArray', $whitelist)
            ->rule('search', 'text_utf8')
            ->rule('page', 'integer')
            ->rule('limit', 'integer');
    }
}
