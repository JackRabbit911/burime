<?php declare(strict_types=1);

namespace Common\Enum;

enum BranchAuthorPermissions: int
{
    case WRITE = 1 << 0;
    case EDIT_POST = 1 << 1;
    case DESIGN =  1 << 2;
    case DIRECTOR = 1 << 3;
    case MODERATE = 1 << 4;
    case MANAGE = 1 << 5;
    case EDIT_BRANCH = 1 << 6;
    case EDIT_STATUS = 1 << 7;

    public function is(int $role)
    {
        return $role & $this->value ? true : false;
    }

    public static function getArray(): array
    {
        return array_combine(
            array_column(self::cases(), 'name'),
            array_column(self::cases(), 'value')
        );
    }
}
