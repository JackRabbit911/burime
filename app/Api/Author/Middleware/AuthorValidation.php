<?php

declare(strict_types=1);

namespace App\Api\Author\Middleware;

use App\Api\Common\Middleware\ApiContractValidation;

class AuthorValidation extends ApiContractValidation
{
    protected function setRules($request)
    {
        $this->validation
            ->rule('author.id', 'integer')
            ->rule('author.owner', 'integer')
            ->rule('author.openclosed', 'required|integer|inRange(0, 3)')
            ->rule('author.alias', 'required|text_utf8|maxWordsCount(8)')
            ->rule('author.info.slogan', 'text_utf8|maxWordsCount(200)')
            ->rule('author.info.info', 'text_utf8|maxWordsCount(200)')
            ->rule('members.id', 'integer')
            ->rule('members.role', 'integer|inRange(0, 255)')
            ->rule('members.status', 'integer|inRange(0, 255)')
            ->rule('members.alias', 'text_utf8')
        ;
    }
}
