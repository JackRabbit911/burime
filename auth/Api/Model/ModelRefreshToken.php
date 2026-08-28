<?php

declare(strict_types=1);

namespace Auth\Api\Model;

use Sys\Model\MysqlModel;
use Pecee\Pixie\Exceptions\DuplicateEntryException;
use Pecee\Pixie\QueryBuilder\IQueryBuilderHandler;
use Pecee\Pixie\QueryBuilder\Transaction;
use Memcached;
use PDO;
use stdClass;

class ModelRefreshToken extends MysqlModel
{
    private string $table = 'refresh_tokens';
    protected array $config;
    private string $user_agent;
    private string $remote_addr;

    public function __construct(protected IQueryBuilderHandler $qb, protected Memcached $cache)
    {
        parent::__construct($qb);
        $this->config = config('o2auth');
        $this->user_agent = $this->hash($_SERVER['HTTP_USER_AGENT']);
        $this->remote_addr = $_SERVER['REMOTE_ADDR'];
    }

    public function initialSesssion(int $user_id, bool $remember): string
    {
        $token = null;
        $this->qb->transaction(function (Transaction $tr) use ($user_id, $remember, &$token) {
            $this->sessionsLimit($tr, $user_id);

            $data = [
                'user_id' => $user_id,
                'lifetime' => ($remember) ? $this->config['remember_lifetime'] : $this->config['refresh_lifetime'],
            ];

            $token = $this->createNewRow($tr, $data);
        });

        return $token;
    }

    public function rotateToken(string $token)
    {
        $token_hash = $this->hash($token);
        $user = $this->getUserByToken($token);

        if ($user) {
            $data = new stdClass;
            $data->lifetime = $user->lifetime;
            unset($user->lifetime);
            $data->user = $user;
            $data->token_hash = $token_hash;

            $this->cache->set('token:' . $token, $user, 10);

            while (true) {
                try {
                    $new_token = bin2hex(random_bytes(16));
                    $new_hash = $this->hash($new_token);

                    $this->qb->table($this->table)
                        ->where('token', '=', $token_hash)
                        ->update(['token' => $new_hash, 'raw' => $new_token]);

                    break;
                } catch (DuplicateEntryException $e) {
                    continue;
                }
            }

            $data->token = $new_token;
        } else {
            $this->qb->table($this->table)
                ->where('token', '=', $token_hash)
                ->delete();
        }

        return $user ? $data : false;
    }

    public function logout(string $token, ?int $user_id = null): int|null
    {
        if (!$user_id) {
            $user = $this->getUserByToken($token, false);
        }

        $this->qb->table($this->table)
            ->where('token', '=', $this->hash($token))
            ->delete();

        return $user?->id ?? null;
    }

    public function logoutGlobal(string $token): int|null
    {
        $user = $this->getUserByToken($token, false);

        if ($user) {
            unset($user->lifetime);
            $this->cache->set('token:' . $token, $user, 10);
            $this->deleteByUser($user->id);
        } else {
            $this->logout($token);
        }

        return $user?->id ?? null;
    }

    public function deleteOthers(string $token)
    {
        $user = $this->getUserByToken($token);

        if (!$user) {
            return;
        }

        unset($user->lifetime);
        $this->cache->set('token:' . $token, $user, 10);

        $this->qb->table($this->table)
            ->where('user_id', '=', $user->id)
            ->where('token', '!=', $this->hash($token))
            ->delete();
    }

    public function deleteByUser(int $user_id): void
    {
        $this->qb->table($this->table)
            ->where('user_id', '=', $user_id)
            ->delete();
    }

    public function gc(): int
    {
        $sql = "DELETE t1
        FROM refresh_tokens t1
        LEFT JOIN refresh_tokens t2 
            ON t1.user_id = t2.user_id 
            AND t1.user_agent = t2.user_agent
            AND t1.remote_addr = t2.remote_addr
            AND t1.created_at < t2.created_at
        WHERE (t1.created_at + INTERVAL t1.lifetime SECOND < NOW())
        OR t2.token IS NOT NULL";

        $pdo = $this->qb->pdo();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function hash(string $token)
    {
        return hash('sha256', $token, true);
    }

    private function sessionsLimit(Transaction $tr, int $user_id): void
    {
        $limit = $this->config['max_active_sessions'];

        $sql = "DELETE FROM refresh_tokens 
        WHERE token NOT IN (
            SELECT token FROM (
                SELECT token FROM refresh_tokens
                WHERE user_id = $user_id
                ORDER BY created_at DESC 
                LIMIT $limit
            ) AS temp
        )";

        $tr->query($sql);
    }

    private function createNewRow(Transaction $tr, array $data): string
    {
        $table = $tr->table($this->table);

        while (true) {
            $token = bin2hex(random_bytes(16));
            $data['raw'] = $token;
            $data['token'] = $this->hash($token);
            $data['user_agent'] = $this->user_agent;
            $data['remote_addr'] = $this->remote_addr;

            try {
                $table->insert($data);
                break;
            } catch (DuplicateEntryException $e) {
                continue;
            }
        }

        return $token;
    }

    public function getUserByToken(string $token, bool $is_expired = true): object|null
    {
        $token_hash = $this->hash($token);
        $expired = $this->qb->raw('(NOW() - INTERVAL lifetime SECOND)');

        $table = $this->qb->table($this->table)
            ->select(
                [
                    'users.id',
                    'users.name',
                    'users.dob',
                    'users.sex',
                    'admins.role',
                    'lifetime',
                ]
            )
            ->join('users', 'users.id', '=', "refresh_tokens.user_id")
            ->leftJoin('admins', 'admins.user_id', '=', 'refresh_tokens.user_id')
            ->where('token', '=', $token_hash);

        if ($is_expired) {
            $table->where('created_at', '>=', $expired)
                ->where('user_agent', '=', $this->user_agent)
                ->where('remote_addr', '=', $this->remote_addr);
        }

        return $table->first();
    }
}
