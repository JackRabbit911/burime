<?php

declare(strict_types=1);

namespace Auth\Middleware;

use Auth\Api\Repository\AuthRepo;
use Auth\Api\Model\ModelRefreshToken;
use Auth\Model\ModelUser;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use UnexpectedValueException;
use stdClass;

class AuthMiddleware implements MiddlewareInterface
{
    private const EXPIRED = 0;
    private const ALARM = 1;
    private array $config = [];

    public function __construct(
        private AuthRepo $repo,
        private ModelRefreshToken $modelRefresh,
        private ModelUser $model,
    ) {
        $this->config = config('o2auth');
    }

    public function process(Request $request, Handler $handler): Response
    {
        $cookie = $request->getCookieParams();
        $user = $this->checkCookie($cookie);

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

    private function checkCookie(array $cookie): object|false
    {
        $result = $this->checkBearer($cookie['OAT'] ?? null);

        if ($result === self::EXPIRED) {
            $user = $this->checkRefresh($cookie['UAT'] ?? null);
        } elseif ($result === self::ALARM) {
            $this->repo->logout($cookie);
            $user = false;
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
            $payload = JWT::decode($token, new Key($this->config['key'], $this->config['algo']));
            return $payload;
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
            $now = time();
            $refresh = $result['token'];
            $bearer = $this->repo->encodeJWT((object) $result['user'], $result['session_id']);

            $options['expires'] = $now + $result['lifetime'];
            setcookie('UAT', $refresh, $options);

            $options['expires'] = $now + $this->config['lifetime'];
            setcookie('OAT', $bearer, $options);

            return $result['user'];
        }

        return false;
    }
}
