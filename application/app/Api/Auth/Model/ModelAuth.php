<?php

declare(strict_types=1);

namespace App\Api\Auth\Model;

use Sys\Model\MysqlModel;

class ModelAuth extends MysqlModel
{
    public function find(int $id){}

    public function auth(string $email, string $password)
    {
        $user = $this->qb->table('users')
            ->select('id', 'name', 'dob', 'sex', 'password')
            ->find($email, 'email');

        if (!$user) {
            return false;
        }

        $hash = $user->password;
        unset($user->password);

        return password_verify($password, $hash) ? $user : false;
    }
}
