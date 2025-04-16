<?php declare(strict_types=1);

namespace App\Branch\Middleware;

use Attribute;
use Az\Validation\Middleware\ValidationMiddleware;
use Psr\Http\Message\ServerRequestInterface;

#[Attribute]
final class BranchCoverValidation extends ValidationMiddleware
{
    protected function setRules($request)
    {
        $this->validation->rule('info[bg_color]', 'hex_color')
            ->rule('info[text_color]', 'hex_color')
            ->rule('cover', 'img|size(2M)');
    }
}
