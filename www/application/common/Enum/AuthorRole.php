<?php declare(strict_types=1);

namespace Common\Enum;

enum AuthorRole: int
{
    case Master = 150;
    case Moderator = 100;
    case Author = 50;

    public static function getRole($string)
    {
        return match ($string) {
            'master' => self::Master->value,
            'moderator' => self::Moderator->value,
            'author' => self::Author->value,
        };
    }

    public static function getRoleString(int $role)
    {
        return match ($role) {
            self::Master->value => 'master',
            self::Moderator->value => 'moderator',
            self::Author->value => 'author',
        };
    }
}
