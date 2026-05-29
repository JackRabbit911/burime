<?php

namespace App\Burime\Middleware;

use Az\Validation\Middleware\ValidationMiddleware;
use Psr\Http\Message\ServerRequestInterface;

class PostValidation extends ValidationMiddleware
{
    protected function setRules(ServerRequestInterface $request)
    {
        $this->validation->rule('new_post', 'text_utf8|maxWordsCount(200)');
    }
}
