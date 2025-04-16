<?php

namespace Auth\Model;

use PDO;
use Sys\Model\Trait\QueryBuilder;

final class ModelUserToken
{
    use QueryBuilder;
    
    const CREATE_TABLE_USERS_TOKENS = [
        'mysql' => "CREATE TABLE `users_tokens` (
            `token` varchar(128) NOT NULL,
            `user_id` int(11) NOT NULL,
            `last_activity` int(10) NOT NULL
          );",

        'sqlite' => "CREATE TABLE users_tokens (
            token text NOT NULL PRIMARY KEY,
            user_id integer NOT NULL,
            last_activity integer NOT NULL
          );",
        ];

    private $table = 'users_tokens';

    public function create($user_agent, $user_id)
    {
        $data['token'] = $this->tokenGenerate();
        $data['user_agent'] = $user_agent;
        $data['user_id'] = $user_id;
        
        $this->qb->table($this->table)->insert($data);

        return $data['token'];
    }

    public function read($token, $lifetime = 0): ?string
    {
        return $this->qb->table($this->table)->select('user_id')
            ->where('last_activity', '>', time() - $lifetime)
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->find($token, 'token');
    }

    public function update($token, $lifetime = 0): string
    {
        $newToken = $this->tokenGenerate();
        $count = $this->qb->table($this->table)->where('token', '=', $token)
            ->update(['token' => $newToken])
            ->rowCount();

        return ($count > 0) ? $newToken : $token;
    }

    public function delete($token)
    {
        $this->qb->table($this->table)
            ->where('token', '=', $token)->delete();
    }

    public function clear($user_agent, $user_id)
    {
        $this->qb->table($this->table)
            ->where('user_agent', '=', $user_agent)
            ->where('user_id', '=', $user_id)
            ->delete();
    }

    public function gc($lifetime = 3600)
    {
        return $this->qb->table($this->table)
            ->where($this->qb->raw('NOW() - `last_activity` > ' . $lifetime))
            ->delete();
    }

    private function tokenGenerate()
    {
        $salt = $_SERVER['HTTP_USER_AGENT'] ?? uniqid();
        return sha1($salt.time().bin2hex(random_bytes(16)));
    }
}
