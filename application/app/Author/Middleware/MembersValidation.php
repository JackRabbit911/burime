<?php declare(strict_types=1);

namespace App\Author\Middleware;

use Attribute;
use Az\Validation\Middleware\ValidationMiddleware;
use Psr\Http\Message\ServerRequestInterface;

#[Attribute]
final class MembersValidation extends ValidationMiddleware
{
    protected function setRules(ServerRequestInterface $request)
    {
        $this->validation->rule('members', 'required|integer')
            ->rule('action', 'required|inList(sendmsg, chat, subscribe, unsubscribe)');
    }
}
