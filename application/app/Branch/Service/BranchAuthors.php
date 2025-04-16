<?php declare(strict_types=1);

namespace App\Branch\Service;

use App\Branch\Branch;
use App\Branch\Model\BranchAuthorsRepo;

use Auth\User;

use Az\Session\SessionInterface;
use Sys\Collection\Collection;

class BranchAuthors
{
    private SessionInterface $session;
    private BranchAuthorsRepo $repo;

    public function __construct(SessionInterface $session, BranchAuthorsRepo $repo)
    {
        $this->session = $session;
        $this->repo = $repo;
    }

    public function getFormData(array $queryParams, User $user, Branch $branch)
    {
        $invites = $this->session->keep('invites') ?? new Collection();

        $filter = $queryParams['filter'] ?? null;
        $invite_id = $queryParams['invite'] ?? null;
        $master_id = $queryParams['master'] ?? null;

        if ($invite_id) {
            $invited = $this->repo->findByBranch($invite_id, $branch->id);
            $invites->offsetSet(null, $invited);
        }

        if ($master_id) {
            $master = $this->repo->findByBranch($invite_id, $branch->id, true);
            $invites->offsetSet(null, $master);
        }

        $this->session->flash('invites', $invites->unique());
        $branch->authors = $branch->authors->merge($invites)->unique();

        return $this->repo->getData($branch, $user->ownAuthors, $filter);
    }
}
