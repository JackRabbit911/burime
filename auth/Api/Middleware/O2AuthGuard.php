<?php

declare(strict_types=1);

namespace Auth\Api\Middleware;

use Auth\Api\Model\ModelRefreshToken;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use HttpSoft\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use UnexpectedValueException;
use stdClass;
use Memcached;

class O2AuthGuard implements MiddlewareInterface
{
    private array $config;

    public function __construct(private Memcached $cache, private ModelRefreshToken $model)
    {
        $this->config = config('o2auth');
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if ($this->exceptUri($request)) {
            return $handler->handle($request);
        }

        $token = $request->getHeaderLine('Authorization');

        if ($token && str_starts_with($token, 'Bearer')) {
            $token = str_replace('Bearer ', '', $token);
            $payload = $this->checkBearer($token);

            if ($payload) { // && $this->checkLogIn($payload->sid) && $this->checkNoBan($payload->user)) {
                $request = $request->withAttribute('user', $payload->user);
                return $handler->handle($request);
            }
        }

        return new EmptyResponse(401);
    }

    private function exceptUri(ServerRequestInterface $request): bool
    {
        $uri = $request->getUri()->getPath();

        foreach ($this->config['exclude_urls'] as $start) {
            if (str_starts_with($uri, $start)) {
                return true;
            }
        }

        return false;
    }

    private function checkBearer(string $token): stdClass|false
    {
        try {
            $payload = JWT::decode($token, new Key($this->config['key'], $this->config['algo']));
            return $payload;
        } catch (UnexpectedValueException $e) {
            return false;
        }
    }

    private function checkLogIn(string $session_id): bool
    {
        $sid = hex2bin($session_id);
        $is_blacklisted = $this->cache->get('blacklist_sid:' . $sid);

        if ($is_blacklisted) {
            return false;
        }

        $resultCode = $this->cache->getResultCode();
        $is_cache_down = ($resultCode !== Memcached::RES_SUCCESS && $resultCode !== Memcached::RES_NOTFOUND);

        if ($is_cache_down) {
            $session_in_db = $this->model->sessionExists($sid);
            return $session_in_db ? true : false;
        }

        return true;
    }

    private function checkNoBan(object $user): bool
    {
        $is_blacklisted = $this->cache->get('blacklist_uid:' . $user->id);
        return $is_blacklisted ? false : true;
    }
}

