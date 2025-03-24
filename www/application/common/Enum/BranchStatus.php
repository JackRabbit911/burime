<?php declare(strict_types=1);

namespace Common\Enum;

enum BranchStatus: int
{
    case Deleted = 0;
    case Draft = 20;
    case Banned = 40;
    case Archive = 80;
    case Publish = 100;
    case Closed = 110;
    case Writing = 120;
    case Waiting = 130;
    case Moderation = 140;
    case Ready = 200;

    public static function isBlocked(int $status): bool
    {
        return $status >= self::Writing->value && $status <= self::Moderation->value;
    }

    public static function isWaiting(int $status): bool
    {
        return $status >= self::Writing->value && $status <= self::Waiting->value;
    }

    public static function getStatus($string)
    {
        return match ($string) {
            'draft' => self::Draft->value,
            'publish' => self::Ready->value,
            default => self::Ready->value,
        };
    }
}
