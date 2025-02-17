<?php declare(strict_types=1);

namespace Common\Enum;

enum BranchStatus: int
{
    case Deleted = 0;
    case Draft = 20;
    case Archive = 80;
    case Publish = 100;
    case Closed = 110;
    case Blocked = 120;
    case Ready = 150;

    public static function getStatus($string)
    {
        return match ($string) {
            'draft' => self::Draft->value,
            'publish' => self::Ready->value,
            default => self::Ready->value,
        };
    }
}
