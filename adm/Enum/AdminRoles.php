<?php

declare(strict_types=1);

namespace Adm\Enum;

enum AdminRoles: int
{
    case Seo = 1 << 0;
    case Burime = 1 << 1;
    case Content = 1 << 2;
    case Users = 1 << 3;
    case Develop = 1 << 4;
    case DevOps = 1 << 5;
    case Admin = 1 << 6;
    case Commerce = 1 << 7;

    public function is(int $role)
    {
        return $role & $this->value ? true : false;
    }
}
