<?php

use Auth\Middleware\OAuthMiddleware;
use Sys\Middleware\CORSMiddleware;

$this->pipe(CORSMiddleware::class);
$this->pipe(OAuthMiddleware::class);
