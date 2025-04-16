<?php declare(strict_types=1);

namespace App\Branch\Model;

use App\Branch\Branch;
use Common\Enum\AuthorRole;
use Common\Enum\BranchAuthorStatus;
use Common\Enum\BranchRole;
use Sys\Collection\Collection;
use Az\Session\SessionInterface;
use Common\Contract\AuthorInterface;
use Common\Contract\IModelAuthor;

class AddAuthorsRepo
{
    private IModelAuthor $model;

    public function __construct(IModelAuthor $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function getAuthorsByPost(array $data, int $user_id): Collection
    {
        foreach ($data as $key => $value) {
            if ($key === 'master') {
                $author = $this->model->find((int) $value);
                $author->user_id = $user_id;
                $author->role = AuthorRole::Master->value;
                $author->status = BranchAuthorStatus::Participant->value;
                $authors[] = $author;
            } else {
                foreach ($value as $aid) {
                    $author = $this->model->find((int) $aid);
                    $author->role = AuthorRole::getRole($key);
                    $author->status = BranchAuthorStatus::Invited->value;
                    $authors[$aid] = $author;
                }
            }
        }

        return new Collection(array_values($authors));
    }

    public function getAuthors(SessionInterface $session, Branch $branch, $user_id, array $data)
    {
        $authorClass = container()->get(AuthorInterface::class);

        $invites = $session->keep('invites') ?? new Collection();
        $au = $branch->authors->merge($invites)->unique();

        foreach ($data as $key => $value) {
            if ($key === 'master') {
                $instance = $au->getInstance((int) $value);
                $author = new $authorClass;
                $author->id = (int) $value;
                $author->user_id = (int) $user_id;
                $author->role = AuthorRole::Master->value;
                $author->status = BranchAuthorStatus::Participant->value;
                $authors[] = $author;
            } else {
                foreach ($value as $aid) {
                    $instance = $au->getInstance((int) $aid);
                    $author = new $authorClass;
                    $author->id = (int) $aid;
                    $author->user_id = $instance->user_id ?? null;
                    $author->role = AuthorRole::getRole($key);
                    $author->status = $this->calcStatus($branch->id, $instance->status);
                    $authors[$aid] = $author;
                }
            }
        }

        return new Collection(array_values($authors));
    }

    private function calcStatus($branch_role, $instance_status)
    {
        if ($branch_role > BranchRole::Open->value 
            && $instance_status == BranchAuthorStatus::Candidate->value) {
            return BranchAuthorStatus::Invited->value;
        }

        return $instance_status;
    }
}
