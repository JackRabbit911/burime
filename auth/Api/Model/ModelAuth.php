<?php

declare(strict_types=1);

namespace Auth\Api\Model;

use stdClass;
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
            ->select('id', 'name', 'dob', 'sex', 'role', 'password')
            ->leftJoin('admins', 'admins.user_id', '=', 'id')
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
        if (!($user = $this->root($email, $password))) {
            $user = $this->auth($email, $password);
        }

        return $user ? true : false;
    }

    public function find(int $id): object | null
    {
        if (self::$user) {
            return self::$user;
        }

        self::$user = $this->qb->table('users')
            ->select('id', 'name', 'dob', 'sex', 'role')
            ->leftJoin('admins', 'admins.user_id', '=', 'id')
            ->find($id);

        return self::$user;
    }

    private function root(string $email, string $password)
    {
        if ($email !== env('ADM_ROOT_EMAIL') || $password !== env('ADM_ROOT_PASSWORD')) {
            return false;
        }

        self::$user = new stdClass;
        self::$user->id = 0;
        self::$user->name = 'Root';
        self::$user->dob = null;
        self::$user->sex = null;
        self::$user->role = 255;

        return self::$user;
    }
}
