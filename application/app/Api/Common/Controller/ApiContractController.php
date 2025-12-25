<?php

declare(strict_types=1);

namespace App\Api\Common\Controller;

use HttpSoft\Response\JsonResponse;
use Az\Route\Route;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Sys\Controller\InvokeTrait;
use Sys\I18n\I18n;
use Throwable;

abstract class ApiContractController implements RequestHandlerInterface // extends BaseController
{
    use InvokeTrait;

    protected ServerRequestInterface $request;
    protected array $parameters;
    protected array $headers;
    protected $user;
    protected I18n $i18n;
    protected int $status = 200;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->request = $request;
        $route = $request->getAttribute(Route::class);
        $this->parameters = $route->getParameters();
        $this->user = $request->getAttribute('user');
        $this->i18n = $request->getAttribute('i18n');

        [, $action] = $route->getHandler();

        try {
            $this->_before();
            $response = $this->call($action, $this->parameters);

            if ($response instanceof ResponseInterface) {
                return $response;
            }

            return $this->_success($response);
        } catch (Throwable $e) {
            $this->logger($e);

            return ENV >= TESTING ? new JsonResponse([
                'success' => false,
                'error' => [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            ], 500) : new JsonResponse('Service Unavailable', 503);
        }
    }

    protected function _success(string|array|object $response): ResponseInterface
    {
        $response = [
            'success' => true,
            'result' => $response,
        ];

        return new JsonResponse($response, $this->status);
    }

    protected function _error(string|array $error, int $status): ResponseInterface
    {
        $response = [
            'success' => false,
            'error' => $error,
        ];

        return new JsonResponse($response, $status);
    }

    protected function logger(Throwable $e): void
    {
        $logger = container()->get(LoggerInterface::class);
        $logger->error($e->getMessage() . ' ' . $e->getFile(), [$e->getLine()]);
    }

    protected function _before() {}
}
