<?php

declare(strict_types=1);

namespace App\Api\Auth\Middleware;

use App\Api\Auth\Model\ModelAuth;
use App\Api\Auth\Service\OAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(private ModelAuth $model, private OAuth $oauth){}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        $data = $request->getBody()->getContents();
        $data = json_decode($data);

        $user = $this->model->auth($data->email, $data->password);

        if ($user) {
            $this->oauth->login($user);
            return $handler->handle($request->withAttribute('user', $user));
        }

        return $handler->handle($request);
    }
}
