<?php

declare(strict_types=1);

namespace Auth\Api\Controller;

use Sys\I18n\I18n;
use Sys\Controller\InvokeTrait;
use Az\Route\Route;
use HttpSoft\Response\JsonResponse;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

abstract class ApiAuthController implements RequestHandlerInterface
{
    use InvokeTrait;

    protected ServerRequestInterface $request;
    protected array $headers;
    protected I18n $i18n;
    protected int $status = 200;
    protected object $data;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() === 'POST') {
            $this->status = 201;
        }

        $this->request = $request;
        $route = $request->getAttribute(Route::class);
        $parameters = $route->getParameters();
        $this->i18n = $request->getAttribute('i18n');
        $this->data = $this->getData($request);

        [, $action] = $route->getHandler();

        try {
            $this->_before();
            $response = $this->call($action, $parameters);

            if ($response instanceof ResponseInterface) {
                return $response;
            }

            return $this->_success($response);
        } catch (Throwable $e) {
            $this->logger($e);

            return $this->_error($e);
        }
    }

    private function _success(string|array|object|bool $response): ResponseInterface
    {
        $response = [
            'success' => true,
            'result' => $response,
        ];

        return new JsonResponse($response, $this->status);
    }

    private function _error(Throwable $e): ResponseInterface
    {
        return ENV >= TESTING ? new JsonResponse([
            'success' => false,
            'error' => [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]
        ], 500) : new JsonResponse('Service Unavailable', 503);
    }

    private function logger(Throwable $e): void
    {
        $logger = container()->get(LoggerInterface::class);
        $logger->error($e->getMessage() . ' ' . $e->getFile(), [$e->getLine()]);
    }

    private function getData($request)
    {
        $json = $request->getBody()->getContents();
        return json_decode($json);
    }

    protected function _before(): void {}
}
