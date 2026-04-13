<?php declare(strict_types=1);

namespace App\Api\Message\Enum;

enum MsgStatus: int
{
    case Important = 120;
    case New = 100;
    case Read = 80;
}
