<?php

namespace Auth\Model;

use Auth\User;
use Sys\Model\ModelEntity;
use Sys\Entity\Entity;

final class ModelUser extends ModelEntity
{
    protected string $table = 'users';
    protected string $entityClass = User::class;
    private ?User $user;

    public function isUniqueEmail(string $email): bool
    {
        return ($this->find($email, 'email')) ? false : true;
    }

    public function isUniqueOrOwnEmail(string $newEmail, string $email): bool
    {
        return $email === $newEmail || $this->isUniqueEmail($newEmail);
    }

    public function isPairEmailPswd(string $password, string $email): bool
    {
        $user = $this->find($email, 'email');

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

    public function isRegisteredEmail(string $email): bool
    {
        $this->user = $this->find($email, 'email');
        return ($this->user) ? true : false;
    }

    public function getUser()
    {
        unset($this->user->password);
        return $this->user;
    }

    public function get()
    {
        return $this->qb->table($this->table)->get();
    }
}
