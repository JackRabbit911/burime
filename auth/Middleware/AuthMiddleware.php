<?php

declare(strict_types=1);

namespace Auth\Middleware;

use Auth\Api\Repository\AuthRepo;
use Auth\Api\Model\ModelRefreshToken;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use HttpSoft\Response\JsonResponse;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use UnexpectedValueException;
use Memcached;
use stdClass;

class AuthMiddleware implements MiddlewareInterface
{
    private const EXPIRED = 0;
    private const ALARM = 1;
    private array $config = [];

    public function __construct(
        private AuthRepo $repo,
        private ModelRefreshToken $modelRefresh,
        private Memcached $cache,
    ) {
        $this->config = config('o2auth');
    }

    public function process(Request $request, Handler $handler): Response
    {
        if ($this->exceptUri($request)) {
            return $handler->handle($request);
        }
        
        $cookie = $request->getCookieParams();
        $user = $this->checkCookie($cookie);

        // if (ENV === DEVELOPMENT) {
        //     $from_url = $request->getHeaderLine('Origin');
        //     $from_port = parse_url($from_url, PHP_URL_PORT);

        //     if ($from_port === env('DEV_FROM_PORT', 5173)) {
        //         $user = $this->repo->find(env('DEV_UID'));
        //     }
        // }

        if ($user) {
            $request = $request->withAttribute('user', $user);
        }

        return $handler->handle($request);
    }

    public function checkCookie(array $cookie): object|false
    {
        $user = false;
        $result = $this->checkBearer($cookie['OAT'] ?? null);

        if ($result === self::EXPIRED) {
            $refresh = $cookie['UAT'] ?? null;

            if ($refresh) {
                $user = $this->checkCache($refresh);

                if (!$user) {
                    $user = $this->checkRefresh($refresh);
                }
            }
        } elseif ($result === self::ALARM) {
            $this->repo->logout($refresh);
        } else {
            $user = $result->user;
        }

        return $user;
    }

    private function checkBearer(?string $token): stdClass|int
    {
        if (!$token) {
            return self::EXPIRED;
        }

        try {
            return JWT::decode($token, new Key($this->config['key'], $this->config['algo']));
        } catch (ExpiredException $e) {
            return self::EXPIRED;
        } catch (UnexpectedValueException $e) {
            return self::ALARM;
        }
    }

    private function checkRefresh(?string $token): stdClass|false
    {
        if (!$token) {
            return false;
        }

        $options = $this->config['cookie'];
        $result = $this->modelRefresh->rotateToken($token);

        if ($result) {
            if (isset($result->token)) {
                $now = time();
                $bearer = $this->repo->encodeJWT((object) $result->user);

                $options['expires'] = $now + $result->lifetime;
                setcookie('UAT', $result->token, $options);

                $options['expires'] = $now + $this->config['lifetime'];
                setcookie('OAT', $bearer, $options);
            }

            return $result->user;
        }

        return false;
    }

    private function checkCache(string $token)
    {
        $hash = $this->modelRefresh->hash($token);
        return $this->cache->get('token:' . $hash);
    }

    private function exceptUri(Request $request): bool
    {
        $uri = $request->getUri()->getPath();

        foreach ($this->config['exclude_urls'] as $start) {
            if (str_starts_with($uri, $start)) {
                return true;
            }
        }

        return false;
    }
}
