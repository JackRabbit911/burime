<?php declare(strict_types=1);

namespace Common\Enum;

enum BranchAuthorStatus: int
{
    case deleted = 50;
    case denied = 70;
    case refused = 80;
    case candidate = 90;
    case invited = 110;
    case invited_informed = 120;
    case member = 200;

    public static function getArray(): array
    {
        return array_combine(
            array_column(self::cases(), 'name'),
            array_column(self::cases(), 'value')
        );
    }

    public static function getStatusString($status)
    {
        return array_search($status, self::getArray());
    }
}
