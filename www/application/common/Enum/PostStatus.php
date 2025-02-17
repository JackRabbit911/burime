<?php declare(strict_types=1);

namespace Common\Enum;

enum PostStatus: int
{
    case Deleted = 0;
    case Draft = 20;
    case Moderation = 80;
    case Publish = 120;
    case Fixed = 140;

    public function allowChange()
    {
        return $this->value < 130;
    }

    public static function getStatus($string)
    {
        return match ($string) {
            'draft' => self::Draft->value,
            'publish' => self::Publish->value,
        };
    }
}

