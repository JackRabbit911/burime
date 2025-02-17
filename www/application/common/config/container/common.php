<?php declare(strict_types = 1);

use App\Author\Author;
use App\Branch\Branch;
use Common\Contract\AuthorInterface;
use Common\Contract\BranchInterface;

return [
    AuthorInterface::class => fn() => Author::class,
    BranchInterface::class => fn() => Branch::class,
];
