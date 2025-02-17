<?php declare(strict_types = 1);

use App\Branch\Branch;
use Common\Contract\BranchInterface;

return [
    BranchInterface::class => fn() => Branch::class,
];
