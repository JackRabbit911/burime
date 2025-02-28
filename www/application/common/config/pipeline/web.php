<?php

use App\Author\Middleware\UserAuthorsMiddleware;
use Auth\Middleware\AuthMiddleware;
use Az\Session\SessionMiddleware;
use Sys\I18n\I18nMiddleware;

$this->pipe(I18nMiddleware::class);
$this->pipe(SessionMiddleware::class);
$this->pipe(AuthMiddleware::class);
$this->pipe(UserAuthorsMiddleware::class);
