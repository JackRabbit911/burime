<?php

use App\Api\Common\Middleware\AuthGuard;
use App\Author\Middleware\UserAuthorsMiddleware;
use Auth\Api\Middleware\O2AuthGuard;
// use Auth\Middleware\OAuthMiddleware;
use Auth\Middleware\AuthMiddleware;
use Sys\Middleware\CORSMiddleware;
use Sys\I18n\I18nMiddleware;

$this->pipe(CORSMiddleware::class);
$this->pipe(I18nMiddleware::class);
$this->pipe(AuthMiddleware::class);
$this->pipe(O2AuthGuard::class, '/api/my');
// $this->pipe(OAuthMiddleware::class, '/api/my');
// $this->pipe(AuthGuard::class, '/api/my');
$this->pipe(UserAuthorsMiddleware::class, '/api/informer');
