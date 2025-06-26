<?php

declare(strict_types=1);

namespace Auth\Api;

class UserJWT
{
    public static function fromObject(object $user)
    {
        return new self($user->id, $user->role);
    }

    public function __construct(
        public readonly int $id,
        public readonly int $role,
    ){}
}
