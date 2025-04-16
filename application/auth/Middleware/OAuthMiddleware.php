<?php declare(strict_types = 1);

namespace Auth\Middleware;

use Auth\Model\OAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class OAuthMiddleware implements MiddlewareInterface
{
    private OAuth $oAuth;

    public function __construct(OAuth $oAuth)
    {
        $this->oAuth = $oAuth;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $this->oAuth->auth($request);

        if ($user) {
            $request = $request->withAttribute('user', $user);
        }
        
        return $handler->handle($request);
    }
}
