<?php

declare(strict_types=1);

namespace App\Api\Auth\Model;

use Sys\Model\MysqlModel;
use Pecee\Pixie\Exceptions\DuplicateEntryException;

class ModelConfirm extends MysqlModel
{
    public function set(object|string|null $user = null)
    {
        if (is_object($user)) {
            $user = json_encode($user, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }

        while(true) {
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
