<?php

declare(strict_types=1);

namespace Adm\Service;

class ADM
{
    public const TRANSLATE = 1 << 0;
    public const SEO = 1 << 1;
    public const BURIME = 1 << 2;
    public const CONTENT = 1 << 3;
    public const DEVELOP = 1 << 4;
    public const USERS = 1 << 5;
    public const ADMIN = 1 << 6;
    public const COMMERCE = 1 << 7;

    public static function is(int $user_role, int $permission): bool
    {
        return ($user_role & $permission) === $permission;
    }
}
