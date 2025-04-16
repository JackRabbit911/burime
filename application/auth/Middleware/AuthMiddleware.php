<?php

namespace Auth\Middleware;

use Auth\Model\ModelUser;
use Auth\Model\TokenAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthMiddleware implements MiddlewareInterface
{
    private TokenAuth $tokenAuth;
    private ModelUser $model;

    public function __construct(TokenAuth $tokenAuth, ModelUser $model)
    {
        $this->tokenAuth = $tokenAuth;
        $this->model = $model;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = $request->getAttribute('session');

        if ($session) {
            $user_id = $session->get('user_id');

            if (!$user_id) {
                $user_id = $this->tokenAuth->auth();
            }

            if ($user_id) {
                $session->set('user_id', $user_id);
                $user = $this->model->find($user_id);
            }

            if (isset($user)) {
                unset($user->password);
                $request = $request->withAttribute('user', $user);
            }
        }
        
        return $handler->handle($request);
    }
}
