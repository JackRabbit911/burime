<?php

use Auth\Api\Middleware\ApiTestMiddleware;
use Auth\Api\Middleware\AuthMiddleware;
use Sys\Middleware\CORSMiddleware;

$this->pipe(CORSMiddleware::class);
$this->pipe(AuthMiddleware::class);
