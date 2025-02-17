<?php declare(strict_types=1);

namespace Common\Enum;

enum BranchRole: int
{
    case Open = 0;
    case Closed = 1;
    case Commercial = 2;
}
