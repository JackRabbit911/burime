<?php declare(strict_types=1);

namespace App\Burime\Service;

use Common\Contract\BranchInterface;
use Common\Enum\BranchRole;
use Common\Enum\BranchStatus;
use Common\Enum\BranchAuthorStatus;
use Common\Enum\BranchAuthorPermissions;
use App\Author\Author;
use stdClass;

class BranchPermissions
{
    const MSG_AGE_LIMIT = 'This work has age restrictions.
Register and indicate your date of birth';

    private BranchInterface $branch;
    private ?object $user;
    private string $msg;
    private ?Author $myAuthor;
    private stdClass $permissions;

    public function __construct(BranchInterface $branch, ?object $user, ?Author $myAuthor)
    {
        $this->branch = $branch;
        $this->user = $user;
        $this->myAuthor = $myAuthor;

        $this->permissions = new stdClass;
        $this->permissions->first = $this->first();
        $this->permissions->show = $this->show();
        $this->permissions->age = $this->age();
        $this->permissions->edit = $this->edit();
        $this->permissions->leave = $this->leave();
        $this->permissions->ask = $this->ask2join();
    }

    public function getPerms()
    {
        return $this->permissions;
    }

    public function first()
    {
        if (!$this->user) {
            return false;
        }
        
        if ($this->branch->maxWeight === 0) {
            if ($this->branch->role === BranchRole::Open->value) {
                return true;
            } elseif (!$this->branch->authors->intersect($this->user->ownAuthors)->empty()
                && $this->myAuthor->role >= BranchAuthorPermissions::WRITE->value) {
                return true;
            }
        }

        return false;
    }

    private function show(): bool
    {
        if ($this->branch->status < BranchStatus::Archive->value 
            && (!$this->myAuthor || $this->myAuthor->role < BranchAuthorPermissions::EDIT_STATUS->value)) {
            return false;
        }

        if ($this->branch->role === BranchRole::Commercial->value 
            && (!$this->myAuthor || $this->myAuthor->status < BranchAuthorStatus::member->value)) {
            return false;
        }

        return true;
    }

    private function age(): bool
    {
        if (isset($this->branch->age_limit) && $this->branch->age_limit > 12) {
            if (!$this->user || $this->user->age() < $this->branch->age_limit) {
                $this->permissions->msg = self::MSG_AGE_LIMIT;
                return false;
            }
        }

        return true;
    }

    private function edit(): bool
    {
        return $this->branch->owner === $this->user?->id;
    }

    private function leave(): bool
    {
        if (!$this->user 
        || !$this->myAuthor 
        || $this->myAuthor->role >= BranchAuthorPermissions::EDIT_STATUS->value
        || $this->myAuthor->status <= BranchAuthorStatus::refused->value) {
            return false;
        }

        return true;
    }

    private function ask2join()
    {
        if ($this->branch->role === BranchRole::Closed->value 
            && !$this->myAuthor && $this->user) {
            return true;
        }

        return false;
    }
}
