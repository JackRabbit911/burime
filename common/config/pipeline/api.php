<?php

// use App\Api\Common\Middleware\AuthGuard;
use App\Author\Middleware\UserAuthorsMiddleware;
use Auth\Api\Middleware\ApiAuthGuard;
use Auth\Middleware\AuthMiddleware;
use Sys\Middleware\CORSMiddleware;
use Sys\I18n\I18nMiddleware;

use Auth\Api\Middleware\O2AuthGuard;
// use Auth\Middleware\OAuthMiddleware;

$this->pipe(CORSMiddleware::class);
$this->pipe(I18nMiddleware::class);
$this->pipe(AuthMiddleware::class, '/api/my');
$this->pipe(ApiAuthGuard::class, '/api/my');
$this->pipe(O2AuthGuard::class, '/api/adm');
// $this->pipe(OAuthMiddleware::class, '/api/my');
// $this->pipe(AuthGuard::class, '/api/my');
// $this->pipe(UserAuthorsMiddleware::class);
