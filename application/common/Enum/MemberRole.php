<?php declare(strict_types=1);

namespace Common\Enum;

enum MemberRole: int
{
    case Friend = 150;
    case Favorive = 100;
    case Addressbook = 50;

    public static function getByFilter($filter)
    {
        return match ($filter) {
            'friends' => self::Friend->value,
            'favorites' => self::Favorive->value,
            'addressbook' => self::Addressbook->value,
            default => null,
        };
    }

    public static function getFilters()
    {
        return [
            'friends',
            'favorites',
            'addressbook',
        ];
    }
}
