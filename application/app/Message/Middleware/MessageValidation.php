<?php

namespace App\Message\Middleware;

use Attribute;
use Az\Validation\Middleware\ValidationMiddleware;
use Psr\Http\Message\ServerRequestInterface;

#[Attribute]
final class MessageValidation extends ValidationMiddleware
{
    protected function setRules(ServerRequestInterface $request)
    {
        $this->validation->rule('from', 'required|integer')
            ->rule('to', 'required|integer')
            ->rule('subject', 'required|text_utf8|maxLength(100)')
            ->rule('data[body]', 'text_utf8|maxLength(2000)');
    }

//    protected function debug($request, $data)
//    {
//        dd($this->validation->getResponse(), $data);
//    }
}
