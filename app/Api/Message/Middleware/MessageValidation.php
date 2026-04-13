<?php

declare(strict_types=1);

namespace App\Api\Message\Middleware;

use App\Api\Common\Middleware\ApiContractValidation;

class MessageValidation extends ApiContractValidation
{
    protected function setRules($request)
    {
        $this->validation
            ->rule('important', 'required|boolean')
            ->rule('recipients', 'required|integer')
            ->rule('message.from', 'required|integer')
            ->rule('message.subject', 'required|text_utf8')
            ->rule('message.data.body', 'text_utf8')
            ->rule('message.data.appeal', 'text_utf8')
            ->rule('message.data.signature', 'text_utf8')
        ;
    }
}
