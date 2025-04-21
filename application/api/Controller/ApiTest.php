<?php declare(strict_types=1);

namespace Api\Controller;

use HttpSoft\Response\JsonResponse;
use Az\Route\Route;
use Sys\Controller\ApiController;

class ApiTest extends ApiController
{
    #[Route(methods: API_ALLOW_METHODS)]
    public function __invoke()
    {
        return new JsonResponse(['foo' => 'bar'], 200, $this->headers);
    }
}
