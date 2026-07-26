<?php

declare(strict_types=1);

namespace Adm\Enum;

enum AdminRoles: int
{
    case Tranlate = 1 << 0;
    case Seo = 1 << 1;
    case Burime = 1 << 2;
    case Content = 1 << 3;
    case Develop = 1 << 4;
    case Users = 1 << 5;
    case Admin = 1 << 6;
    case Commerce = 1 << 7;

    public function is(int $role)
    {
        return $role & $this->value ? true : false;
    }
}
