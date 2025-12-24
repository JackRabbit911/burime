<?php

declare(strict_types=1);

namespace Common\Service;

use UnitEnum;

class Permissions
{
    public function __construct(private ?int $permissions) {}

    public function isAllow(UnitEnum ...$actions)
    {
        $action = array_reduce(
            $actions,
            fn($carry, $item) => ($carry | $item->value),
            0
        );

        return (bool) ($this->permissions & $action);
    }

    public function isAllowStrict(UnitEnum ...$actions)
    {
        foreach ($actions as $action) {
            if (!(bool) ($this->permissions & $action->value)) {
                return false;
            }
        }

        return true;
    }
}
