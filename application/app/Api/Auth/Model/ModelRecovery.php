<?php

declare(strict_types=1);

namespace App\Api\Auth\Model;

use Sys\Model\MysqlModel;

class ModelRecovery extends MysqlModel
{
    private static $user;

    public function findByEmail(string $email): object|false
    {
        if (self::$user) {
            return self::$user;
        }

        self::$user = $this->qb->table('users')
            ->select('id', 'name', 'email')
            ->find($email, 'email');

        return self::$user ?: false;
    }

    public function isRegisteredEmail(string $email): bool
    {
        $user = $this->findByEmail($email);
        return $user ? true : false;
    }
}
