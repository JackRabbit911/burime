<?php

declare(strict_types=1);

namespace Auth\Api\Model;

use Auth\Api\UserDTO;
use Auth\Api\UserJWT;
use Auth\Component\Avatar;
use Pecee\Pixie\QueryBuilder\IQueryBuilderHandler;
use stdClass;
use Sys\Model\MysqlModel;

class ModelAuth extends MysqlModel
{
    private int $adminGroupId = 19;
    private stdClass $user;

    public function __construct(protected IQueryBuilderHandler $qb)
    {
        parent::__construct($qb);
    }

    public function isPairEmailPswd(string $password, string $email): bool
    {
        $user = $this->qb->table('users')
            ->select('id', 'name', 'password')
            ->find($email, 'email');

        if (!$user) {
            return false;
        }

        if (password_verify($password, $user->password)) {
            $this->user = $user;
            return true;
        } else {
            return false;
        }
    }

    public function find(int|string $id, string $column = 'user_id'): ?UserJWT
    {
        $user = $this->qb->table('users_authors')
            ->select($this->qb->raw('user_id as id'))
            ->select('role')
            ->where('author_id', '=', $this->adminGroupId)
            ->find($id, $column);

        if (!$user) {
            return null;
        }
        
        $user->avatar = Avatar::getSrc($id);

        return ($user) ? UserJWT::fromObject($user) : null;
    }

    public function get(): UserDTO
    {
        $user = $this->qb->table('users_authors')
            ->select('role')
            ->where('author_id', '=', $this->adminGroupId)
            ->find($this->user->id, 'user_id');

        $user->id = $this->user->id;
        $user->name = $this->user->name;
        $user->avatar = Avatar::getSrc($this->user->id);

        return UserDTO::fromObject($user);
    }
}
