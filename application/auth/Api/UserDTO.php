<?php

declare(strict_types=1);

namespace Auth\Api;

class UserDTO
{
    public static function fromObject(object $user)
    {
        return new self(
            $user->id,
            $user->name,
            $user->role,
            $user->avatar);
    }

    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $role,
        public readonly string $avatar,
    ){}
}
