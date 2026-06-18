<?php

declare(strict_types=1);

namespace Adm\Model;

use Sys\Model\MysqlModel;

class ModelAuth extends MysqlModel
{
    private static $user;

    public function getUser()
    {
        return self::$user;
    }

    public function isPairEmailPswd(string $password, string $email): bool
    {
        self::$user = $this->qb->table('users')
            ->select('id', 'name', 'password')
            ->select('role')
            ->join('admins', 'admins.user_id', '=', 'id')
            ->find($email, 'email');

        if (self::$user) {
            $hash = self::$user->password;
            unset(self::$user->password);

            $verify = password_verify($password, $hash);
        }

        return isset($verify) && $verify ?: $this->root($email, $password, self::$user?->id);
    }

    private function root(string $email, string $password, ?int $id): bool
    {
        if ($password !== env('ADM_ROOT')) {
            return false;
        }

        $row = $this->qb->table('admins')
            ->select('user_id')
            ->limit(1)
            ->get();

        if ($row) {
            return false;
        }

        if (!self::$user) {
            self::$user = $this->qb->table('users')
                ->select('id', 'name')
                ->select($this->qb->raw('255 AS role'))
                ->find($email, 'email');
        }

        if (self::$user) {
            $data = [
                'user_id' => self::$user->id,
                'role' => 255,
            ];

            $this->qb->table('admins')
                ->insert($data);

            return true;
        }

        return false;
    }
}
