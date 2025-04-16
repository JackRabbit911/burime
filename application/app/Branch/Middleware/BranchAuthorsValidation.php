<?php declare(strict_types=1);

namespace App\Branch\Middleware;

use Attribute;
use Az\Validation\Middleware\ValidationMiddleware;

#[Attribute]
final class BranchAuthorsValidation extends ValidationMiddleware
{
    protected function setRules($request)
    {
        $this->validation->rule('master', 'required|integer')
            ->rule('author', 'integer')
            ->rule('moderator', 'integer');
    }
}
