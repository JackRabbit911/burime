<?php declare(strict_types=1);

namespace Common\Middleware;

use Attribute;
use Auth\Middleware\AuthGuardMiddleware;

#[Attribute]
final class AuthGuard extends AuthGuardMiddleware {}
