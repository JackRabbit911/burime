<?php

namespace App\Author\Middleware;

use Attribute;
use Az\Validation\Middleware\ValidationMiddleware;
use Psr\Http\Message\ServerRequestInterface;

#[Attribute]
final class AuthorValidation extends ValidationMiddleware
{
    protected function setRules(ServerRequestInterface $request)
    {
        $this->validation->rule('alias', 'required|username')
            ->rule('info[slogan]', 'required|text_utf8|maxLength(80)')
            ->rule('info[info]', 'text_utf8|maxLength(300)')
            ->rule('avatar', 'size(1M)|img');
    }

//    protected function debug($request)
//    {
//        dd($this->validation->getResponse(), $request->getUploadedFiles());
//    }
}
