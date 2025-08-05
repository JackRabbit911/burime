<?php

declare(strict_types=1);

use Auth\Command\ClearTokens;
use Auth\Command\FakeUsers;
use Symfony\Component\Console\Command\Command;

return [
    'fake:users' => static fn(): Command => container()->get(FakeUsers::class),
    'clear:tokens' => static fn(): Command => container()->get(ClearTokens::class),
];
