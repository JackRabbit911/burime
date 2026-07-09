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
                'is_api' => 0,
                'lifetime' => ($remember) ? $this->config['remember_lifetime'] : $this->config['refresh_lifetime'],
            ];

            $token = $this->createNewRow($tr, $data);
        });

        return $token;
    }

    public function rotateToken(string $token)
    {
        $token_hash = $this->hash($token);
        $expired = $this->qb->raw('(NOW() - INTERVAL lifetime SECOND)');

        $user = $this->getUserByToken($token);

        if ($user) {
            $data = new stdClass;
            $data->lifetime = $user->lifetime;
            unset($user->lifetime);
            $data->user = $user;
            $data->token_hash = $token_hash;

            while (true) {
                try {
                    $new_token = bin2hex(random_bytes(16));
                    $new_hash = $this->hash($new_token);
        
                    $this->qb->table($this->table)
                        ->where('token', '=', $token_hash)
                        ->update(['token' => $new_hash]);

                    break;
                } catch (DuplicateEntryException $e) {
                    continue;
                }
            }

            $this->cache->set('token:' . $token_hash, $user, 10);
            $data->token = $new_token;
        } else {
            $this->qb->table($this->table)
                ->where('token', '=', $token_hash)
                ->delete();
        }

        return $row ? $data : false;
    }

    public function logout(string $token): string
    {
        $this->qb->table($this->table)
            ->where('token', '=', $this->hash($token))
            ->delete();

        return $session_id;
    }

    public function logoutGlobal(string $token): array
    {
        $user = $this->getUserByToken($token);
        unset($user->lifetime);
        $this->cache->set('token:' . $token_hash, $user, 10);
        $this->deleteByUser($user->id);
    }

    public function deleteByUser(int $user_id): void
    {
        $this->qb->table($this->table)
            ->where('user_id', '=', $user_id)
            ->delete();
    }

    public function gc(): int
    {
        $pdo = $this->qb->pdo();

        $sql = "DELETE FROM `refresh_tokens`
        WHERE `created_at` < (NOW() - INTERVAL lifetime SECOND)
        OR (`invalidated_at` IS NOT NULL AND `invalidated_at` < NOW())";

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
        $activeSessionsCount = $tr->table($this->table)
            ->where('user_id', '=', $user_id)
            ->whereNull('invalidated_at')
            ->count();

        if ($activeSessionsCount >= $this->config['max_active_sessions']) {
            $oldestSession = $tr->table('user_refresh_tokens')
                ->select('session_id')
                ->where('user_id', '=', $user_id)
                ->whereNull('invalidated_at')
                ->orderBy('expires_at', 'ASC')
                ->setFetchMode(PDO::FETCH_COLUMN)
                ->first();

            if ($oldestSession) {
                $tr->table('user_refresh_tokens')
                    ->where('session_id', '=', $oldestSession)
                    ->delete();
            }
        }
    }

    private function createNewRow(Transaction $tr, array $data): string
    {
        $table = $tr->table($this->table);

        while (true) {
            $token = bin2hex(random_bytes(16));
            $data['token'] = $this->hash($token);
            $data['user_agent'] = $this->user_agent;
            $data['remote_addr'] = $this->remote_addr;

            if (!isset($data['session_id'])) {
                $data['session_id'] = $data['token'];
            }

            try {
                $table->insert($data);
                break;
            } catch (DuplicateEntryException $e) {
                continue;
            }
        }

        return $token;
    }

    private function getUserByToken(string $token): object|null
    {
        return $this->qb->table($this->table)
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
            ->where('token', '=', $token_hash)
            ->where('user_agent', '=', $this->user_agent)
            ->where('remote_addr', '=', $this->remote_addr)
            ->where('created_at', '>=', $expired)
            ->first();
    }
}
