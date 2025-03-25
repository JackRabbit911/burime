<?php declare(strict_types=1);

namespace App\Branch\Model;

use App\Branch\Branch;
use Common\Enum\AuthorRole;

use App\Author\Model\ModelAuthor;

class BranchAuthorsRepo
{
    private ModelAuthor $model;

    public function __construct(ModelAuthor $model)
    {
        $this->model = $model;
    }

    public function getData(Branch $branch, $users_authors, $filter)
    {
        $au = $this->authorsByBranch($branch->authors ?? []);
        $owners = $branch->authors->props('owner')->all();
        $except = $this->model->getAuthorsIdsByOwners($owners);
        $except = $users_authors->props()->merge($except)->unique()->all();

        return [
            'id' => $branch->id ?? null,
            'authors' => $this->model->getByFilter($filter, $except),
            'myAuthors' => $users_authors,
            'master' => $users_authors->getInstance($au['master'] ?? 0),
            'invites' => $this->model->getByIds($au['author'] ?? []),
            'moders' => $au['moderator'] ?? [],
        ];
    }

    public function findByBranch($author_id, $branch_id, $is_master = false)
    {
        return $this->model->findByBranch($author_id, $branch_id, $is_master);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    private function authorsByBranch($branchAuthors)
    {
        foreach ($branchAuthors as $author)
        {
            switch ($author->role) {
                case AuthorRole::Master->value:
                    $authors['master'] = $author->id;
                    break;
                case AuthorRole::Moderator->value:
                    $authors['moderator'][] = $author->id;
                case AuthorRole::Author->value:
                    $authors['author'][] = $author->id;
                    break;
            }
        }

        return $authors ?? [];
    }
}
