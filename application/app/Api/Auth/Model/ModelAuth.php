<?php

declare(strict_types=1);

namespace App\Api\Auth\Model;

use Sys\Model\MysqlModel;

class ModelAuth extends MysqlModel
{
    private static $user;

    public function auth(string $email, string $password): object|false
    {
        if (self::$user) {
            return self::$user;
        }

        self::$user = $this->qb->table('users')
            ->select('id', 'name', 'dob', 'sex', 'password')
            ->find($email, 'email');

        if (!self::$user) {
            return false;
        }

        $hash = self::$user->password;
        unset(self::$user->password);

        return password_verify($password, $hash) ? self::$user : false;
    }

    public function isPairEmailPswd(string $password, string $email): bool
    {
        $user = $this->auth($email, $password);

        return $user ? true : false;
    }
}
