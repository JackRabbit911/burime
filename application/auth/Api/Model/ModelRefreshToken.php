<?php

declare(strict_types=1);

namespace Auth\Api\Model;

use stdClass;
use Sys\Model\MysqlModel;

class ModelRefreshToken extends MysqlModel
{
    private string $table = 'refresh_tokens';

    public function create(array $data)
    {
        $data['is_api'] = 1;
        $this->qb->table($this->table)->insert($data);
    }

    public function read(string $token, string $user_agent, int $lifetime): stdClass|null
    {
        $expire = date('Y-m-d h:i:s', time() - $lifetime);

        return $this->qb->table($this->table)
            ->select('token', 'user_id', 'updated')
            ->where('user_agent', '=', $user_agent)
            ->where('is_api', '=', 1)
            ->where('updated', '>', $expire)
            ->find($token, 'token');
    }

    public function update(string $token, string $user_agent, array $data): void
    {
        $this->qb->table($this->table)
            ->where('token', '=', $token)
            ->where('user_agent', '=', $user_agent)
            ->where('is_api', '=', 1)
            ->update($data);
    }

    public function delete(string $token, string $user_agent): void
    {
        $this->qb->table($this->table)
            ->where('token', '=', $token)
            ->where('user_agent', '=', $user_agent)
            ->where('is_api', '=', 1)
            ->delete();
    }

    public function gc(int $lifetime): mixed
    {
        $query = $this->qb->table($this->table)
            ->where('updated', '<', $this->qb->raw("NOW() - INTERVAL $lifetime SECOND"))
            ->where('is_api', '=', 1);
        
        $count = $query->count();
        $query->delete();

        return $count;
    }
}
