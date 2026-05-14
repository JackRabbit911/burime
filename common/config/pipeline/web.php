<?php

declare(strict_types=1);

use App\Author\Middleware\UserAuthorsMiddleware;
use Auth\Middleware\OAuthMiddleware;
use Az\Session\SessionMiddleware;
use Sys\I18n\I18nMiddleware;

$this->pipe(I18nMiddleware::class);
$this->pipe(SessionMiddleware::class);
$this->pipe(OAuthMiddleware::class);
$this->pipe(UserAuthorsMiddleware::class);
