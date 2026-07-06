<?php

declare(strict_types=1);

namespace Auth\Model;

use Sys\Model\MysqlModel;
use Pecee\Pixie\Exceptions\DuplicateEntryException;
use Pecee\Pixie\QueryBuilder\IQueryBuilderHandler;
use Pecee\Pixie\QueryBuilder\Transaction;
use PDO;

class ModelRefreshToken extends MysqlModel
{
    private const MAX_ACTIVE_SESSIONS = 5;
    private string $table = 'refresh_tokens';
    private array $config;

    public function __construct(IQueryBuilderHandler $qb)
    {
        parent::__construct($qb);
        $this->config = config('o2auth');
    }

    public function initialSesssion(int $user_id): string
    {
        $token = null;
        $this->qb->transaction(function (Transaction $tr) use ($user_id, &$token) {
            $this->sessionsLimit($tr, $user_id);

            $data = [
                'user_id' => $user_id,
                'is_api' => 0,
            ];

            $token = $this->createNewRow($tr, $data);
        });

        return $token;
    }

    public function rotateToken(string $token): array|false
    {
        $result;
        $token_hash = md5($token);

        $now = $this->qb->query('SELECT NOW()')
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->first();

        $this->qb->transaction(function (Transaction $tr) use ($token_hash, $now, &$result) {
            $select_token = "SELECT token, session_id, user_id, invalidated_at
            FROM refresh_tokens 
            WHERE token = ? AND created_at >= (NOW() - INTERVAL ? SECOND)
            FOR UPDATE";

            $select_user = "SELECT id, name, sex, dob FROM users
            -- JOIN admins ON admins.user_id = users.id
            WHERE id = ?";

            $row = $tr->query($select_token, [
                $token_hash,
                $this->config['refresh_lifetime'],
            ])->first();

            if (!$row) {
                $result = false;
                return;
            }

            $user = $tr->query($select_user, [$row->user_id])->first();

            if (!$row->invalidated_at) {
                $data = [
                    'session_id' => $row->session_id,
                    'user_id' => $row->user_id,
                    'is_api' => 1,
                ];

                $new_token = $this->createNewRow($tr, $data);

                $update = "UPDATE refresh_tokens
                SET invalidated_at = DATE_ADD(NOW(), INTERVAL 10 SECOND)
                WHERE token = ?";

                $tr->query($update, [$token_hash]);

                $delete = "DELETE t1 
                    FROM refresh_tokens t1
                    JOIN (
                        SELECT MAX(`created_at`) AS max_time 
                        FROM refresh_tokens
                    ) t2 ON t1.created_at < t2.max_time";

                $tr->query($delete);

                $result = [
                    'user' => $user,
                    'token' => $new_token,
                    'session_id' => $row->session_id,
                ];
            } elseif ($row->invalidated_at > $now) {
                $result = [
                    'user' => $user,
                    'token' => '',
                    'session_id' => $row->session_id
                ];
            } else {
                $tr->table($this->table)
                    ->where('session_id', '=', $row->session_id)
                    ->delete();

                $result = false;
            }
        });

        return $result;
    }

    public function logout(string $token): string
    {
        $session_id = $this->qb->table($this->table)
            ->select('session_id')
            ->where('token', '=', md5($token))
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->first();

        $this->qb->table($this->table)
            ->where('session_id', '=', $session_id)
            ->delete();

        return $session_id;
    }

    public function logoutGlobal(string $token): array
    {
        $user_id = $this->qb->table($this->table)
            ->select('user_id')
            ->where('token', '=', md5($token))
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->first();

        if (!$user_id) {
            return [];
        }

        $session_ids = $this->qb->table($this->table)
            ->select('session_id')
            ->where('user_id', '=', $user_id)
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->get();

        $this->qb->table($this->table)
            ->where('user_id', '=', $user_id)
            ->delete();

        return $session_ids;
    }

    public function sessionExists(string $session_id): bool
    {
        $row = $this->qb->table($this->table)
            ->select('user_id')
            ->where('session_id', '=', $decoded->sid)
            ->whereNull('invalidated_at') // Если invalidated_at заполнено — сессия закрыта
            ->first();

        return $row ? true : false;
    }

    public function deleteByUser(int $user_id): void
    {
        $this->qb->table($this->table)
            ->where('user_id', '=', $user_id)
            ->delete();
    }

    public function gc(): int
    {
        $lifetime = $this->config['refresh_lifetime'];
        $pdo = $this->qb->pdo();

        $sql = "DELETE FROM `refresh_tokens`
        WHERE `created_at` < (NOW() - INTERVAL $lifetime SECOND)
        OR (`invalidated_at` IS NOT NULL AND `invalidated_at` < NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->rowCount();
    }

    private function sessionsLimit(Transaction $tr, int $user_id): void
    {
        $activeSessionsCount = $tr->table('user_refresh_tokens')
            ->where('user_id', '=', $user_id)
            ->whereNull('invalidated_at')
            ->count();

        if ($activeSessionsCount >= self::MAX_ACTIVE_SESSIONS) {
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
            $data['token'] = md5($token);

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
}
