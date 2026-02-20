<?php

declare(strict_types=1);

namespace App\Api\Auth\Model;

use Sys\Model\MysqlModel;
use PDO;

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

    public function setCode(string $code)
    {
        $this->qb->table('confirm_codes')
            ->insert(['code' => $code]);
    }

    public function getCode(string $code)
    {
        $table = $this->qb->table('confirm_codes')
            ->where('code', '=', $code);
        
        $result = $table->select('code')
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->first();

        $table->delete();

        return $result;
    }
}
