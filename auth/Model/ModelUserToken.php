<?php

namespace Auth\Model;

use PDO;
use Sys\Model\MysqlModel;

class ModelUserToken extends MysqlModel
{
    private $table = 'refresh_tokens';

    public function create(string $user_agent, int $user_id, int $is_api = 0): string
    {
        $data['token'] = $this->tokenGenerate();
        $data['user_agent'] = $user_agent;
        $data['user_id'] = $user_id;
        $data['is_api'] = $is_api;
        
        $this->qb->table($this->table)->insert($data);

        return $data['token'];
    }

    public function read(string $token, string $user_agent, int $lifetime = 0): ?int
    {
        return $this->qb->table($this->table)->select('user_id')
            ->where('updated', '>', time() - $lifetime)
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->find($token, 'token');
    }

    public function update(string $token, string $user_agent, int $lifetime = 0): string
    {
        $newToken = $this->tokenGenerate();
        $count = $this->qb->table($this->table)
            ->where('token', '=', $token)
            ->where('user_agent', '=', $user_agent)
            ->update(['token' => $newToken])
            ->rowCount();

        return ($count > 0) ? $newToken : $token;
    }

    public function delete(string $token, string $user_agent)
    {
        $this->qb->table($this->table)
            ->where('token', '=', $token)
            ->where('user_agent', '=', $user_agent)
            ->delete();
    }

    public function clear($user_agent, $user_id)
    {
        $this->qb->table($this->table)
            ->where('user_agent', '=', $user_agent)
            ->where('user_id', '=', $user_id)
            ->where('is_api', '=', 0)
            ->delete();
    }

    public function gc($lifetime = 3600)
    {
        return $this->qb->table($this->table)
            ->where($this->qb->raw('NOW() - `updated` > ' . $lifetime))
            ->where('is_api', '=', 0)
            ->delete();
    }

    private function tokenGenerate()
    {
        $salt = $_SERVER['HTTP_USER_AGENT'] ?? uniqid();
        return sha1($salt.time().bin2hex(random_bytes(16)));
    }
}
