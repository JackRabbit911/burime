<?php declare(strict_types = 1);

use App\Author\Author;
use App\Author\Model\ModelAuthor;
use App\Author\Model\ModelGroup;
use App\Author\Model\ModelUserGroup;
use App\Branch\Branch;
use App\Burime\Model\FindBranch;
use App\Message\Model\ModelMessage;
use Common\Contract\AuthorInterface;
use Common\Contract\BranchInterface;
use Common\Contract\IFindBranch;
use Common\Contract\IModelAuthor;
use Common\Contract\IModelGroup;
use Common\Contract\IModelMessage;
use Common\Contract\IModelUserGroup;
use Psr\Container\ContainerInterface;

return [
    AuthorInterface::class => fn() => Author::class,
    BranchInterface::class => fn() => Branch::class,
    IModelUserGroup::class => fn() => new ModelUserGroup,
    IModelMessage::class => fn() => new ModelMessage,
    IFindBranch::class => fn(ContainerInterface $c) => new FindBranch($c),
    IModelGroup::class => fn() => new ModelGroup,
    IModelAuthor::class => fn() => new ModelAuthor,
];
