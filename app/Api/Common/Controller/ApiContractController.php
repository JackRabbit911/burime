<?php

declare(strict_types=1);

namespace App\Api\Common\Controller;

use Az\Route\Route;
use Sys\I18n\I18n;
use Sys\Controller\InvokeTrait;
use HttpSoft\Response\JsonResponse;
use HttpSoft\Response\EmptyResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

abstract class ApiContractController implements RequestHandlerInterface // extends BaseController
{
    use InvokeTrait;

    protected ServerRequestInterface $request;
    protected array $parameters;
    protected array $headers;
    protected array $data = [];
    protected $user;
    protected ?I18n $i18n;
    protected int $status = 200;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->request = $request;
        $route = $request->getAttribute(Route::class);
        $this->parameters = $route->getParameters();
        $this->user = $request->getAttribute('user');
        $this->i18n = $request->getAttribute('i18n');

        if ($request->getMethod() === 'POST') {
            $this->status = 201;
        }

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $this->data = $this->getData($request);
        }

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
            return $this->_error($e);
        }
    }

    private function _success(string|array|object|null $response): ResponseInterface
    {
        return !$response ? new EmptyResponse($this->status)
            : new JsonResponse(['success' => true, 'result' => $response], $this->status);
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

    protected function logger(Throwable $e): void
    {
        $logger = container()->get(LoggerInterface::class);
        $logger->error($e->getMessage() . ' ' . $e->getFile(), [$e->getLine()]);
    }

    private function getData($request)
    {
        $data = $request->getParsedBody();

        if (empty($data)) {
            $json = $request->getBody()->getContents();
            $data = json_decode($json, true);
        }

        return (($data)) ?: [];
    }

    protected function _before(): void {}
}
