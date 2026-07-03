<?php

declare(strict_types=1);

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
use Sys\CSRF\Driver\Db;
use Sys\CSRF\Driver\DriverInterface;

return [
    Memcached::class => function () {
        $config = config('memcache');
        $memcached = new Memcached();
        
        if (empty($memcached->getServerList())) {
            $memcached->addServer($config['host'], $config['port']);
            $memcached->setOptions($config['options']);
        }

        return $memcached;
    },
    DriverInterface::class => DI\get(Db::class),
    AuthorInterface::class => fn() => Author::class,
    BranchInterface::class => fn() => Branch::class,
    IModelUserGroup::class => fn() => new ModelUserGroup,
    IModelMessage::class => fn() => new ModelMessage,
    IFindBranch::class => fn(ContainerInterface $c) => new FindBranch($c),
    IModelGroup::class => fn() => new ModelGroup,
    IModelAuthor::class => fn() => new ModelAuthor,
];
