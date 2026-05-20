<?php

use App\Api\Common\Middleware\AuthGuard;
use App\Author\Middleware\UserAuthorsMiddleware;
use Auth\Middleware\OAuthMiddleware;
use Sys\Middleware\CORSMiddleware;
use Sys\I18n\I18nMiddleware;

$this->pipe(CORSMiddleware::class);
$this->pipe(I18nMiddleware::class);
$this->pipe(OAuthMiddleware::class);
$this->pipe(AuthGuard::class, '/api/my');
$this->pipe(UserAuthorsMiddleware::class, '/api/informer');
