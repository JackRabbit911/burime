<?php

declare(strict_types=1);

namespace Auth\Api\Model;

use Sys\Model\MysqlModel;
use Pecee\Pixie\Exceptions\DuplicateEntryException;
use PDO;

class ModelConfirm extends MysqlModel
{
    public function set(object|string|null $user = null)
    {
        if (is_object($user)) {
            $user = json_encode($user, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        while (true) {
            $code = bin2hex(random_bytes(16));

            try {
                $this->qb->table('confirm_codes')
                    ->insert(['code' => $code, 'user' => $user]);

                break;
            } catch (DuplicateEntryException $e) {
                continue;
            }
        }

        return $code;
    }

    public function check(string $code, $uid)
    {
        $table = $this->qb->table('confirm_codes')
            ->select('user')
            ->where('code', '=', $code);

        $json = $table->setFetchMode(PDO::FETCH_COLUMN)
            ->first();

        $user = $json ? json_decode($json) : null;

        $result = $uid == $user?->id ?: false;
        $table->delete();

        return $result;
    }

    public function get(string $code)
    {
        $table = $this->qb->table('confirm_codes')
            ->where('code', '=', $code);

        $result = $table->select('code', 'user')
            ->first();

        $table->delete();

        return $result;
    }
}
