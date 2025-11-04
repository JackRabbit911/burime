<?php

declare(strict_types=1);

namespace App\Branch\Api\Controller;

use HttpSoft\Response\JsonResponse;
use Az\Route\Route;
use HttpSoft\Response\EmptyResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sys\Controller\InvokeTrait;
use Sys\I18n\I18n;
use Throwable;
use Whoops\Handler\JsonResponseHandler;

abstract class ApiContractController implements RequestHandlerInterface // extends BaseController
{
    use InvokeTrait;

    protected ServerRequestInterface $request;
    protected array $parameters;
    protected array $headers;
    protected $user;
    protected I18n $i18n;

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

            $response = [
                'success' => true,
                'result' => $response,
            ];

            $this->_after($response);

            return new JsonResponse($response, 200);
        } catch (Throwable $e) {
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

    protected function _before() {}

    protected function _after(&$response) {}
}
