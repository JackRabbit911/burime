<?php declare(strict_types = 1);

namespace Auth\Middleware;

use Auth\Model\ModelUser;
use Auth\Model\OAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class OAuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private OAuth $oAuth,
        private ModelUser $model){}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $this->oAuth->auth($request);

        if (ENV === DEVELOPMENT) {
            $from_url = $request->getHeaderLine('Origin');
            $from_port = parse_url($from_url, PHP_URL_PORT);

            if ($from_port === env('DEV_FROM_PORT', 5173)) {
                $user = $this->model->find(env('DEV_UID'));
            }
        }

        if ($user) {
            $request = $request->withAttribute('user', $user);
        }
        
        return $handler->handle($request);
    }
}
