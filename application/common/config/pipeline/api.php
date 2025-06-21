<?php

use Auth\Api\Middleware\O2AuthGuard;
use Sys\I18n\I18nMiddleware;
use Sys\Middleware\CORSMiddleware;

$this->pipe(CORSMiddleware::class);
$this->pipe(I18nMiddleware::class);
$this->pipe(O2AuthGuard::class);
