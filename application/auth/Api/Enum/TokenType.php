<?php

declare(strict_types=1);

namespace Auth\Api\Enum;

enum TokenType
{
    case Bearer;
    case Refresh;
    case Unknown;

    public static function detect($token)
    {
        return match (true) {
            str_starts_with($token, 'Bearer') => self::Bearer,
            str_starts_with($token, 'Refresh') => self::Refresh,
            default => self::Unknown,
        };
    }
}
