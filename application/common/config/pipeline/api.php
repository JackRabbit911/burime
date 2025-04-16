<?php

use Auth\Middleware\OAuthMiddleware;

$this->pipe(OAuthMiddleware::class);
