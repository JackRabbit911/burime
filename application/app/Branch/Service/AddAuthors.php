<?php declare(strict_types=1);

namespace App\Branch\Service;

use App\Branch\Branch;
use App\Branch\Model\BranchAuthorsRepo;
use Common\Enum\AuthorRole;
use Common\Enum\BranchAuthorStatus;

use App\Author\Entity\Author;

use Az\Session\SessionInterface;
use Sys\Collection\Collection;

class AddAuthors
{
    private SessionInterface $session;
    private BranchAuthorsRepo $repo;

    public function __construct(SessionInterface $session, BranchAuthorsRepo $repo)
    {
        $this->session = $session;
        $this->repo = $repo;
    }

    public function getFormData($queryParams, $user, Branch &$branch)
    {
        $invites = $this->session->keep('invites') ?? new Collection();

        $filter = $queryParams['filter'] ?? null;
        $invite_id = $queryParams['invite'] ?? null;
        $master_id = $queryParams['master'] ?? null;

        if ($invite_id) {
            $invited = $this->repo->find($invite_id);
            $invited->role = AuthorRole::Author->value;
            $invited->status = BranchAuthorStatus::Invited->value;
            $invites->offsetSet(null, $invited);
        }

        if ($master_id) {
            $invited = $invites->getInstance($master_id) ?? new Author;
            $invited->id = $master_id;
            $invited->role = AuthorRole::Master->value;
            $invited->status = BranchAuthorStatus::Participant->value;
            $invites->offsetSet(null, $invited);
        }

        $this->session->set('invites', $invites->unique());
        $branch->authors = (isset($branch->authors))
            ? $branch->authors->merge($invites)->unique()
            : $invites;

        $fd = $this->repo->getData($branch, $user->ownAuthors, $filter);

        return $fd;
    }
}
