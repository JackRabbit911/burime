<?php

use Sys\Middleware\CORSMiddleware;
use Sys\I18n\I18nMiddleware;

$this->pipe(CORSMiddleware::class);
$this->pipe(I18nMiddleware::class);
