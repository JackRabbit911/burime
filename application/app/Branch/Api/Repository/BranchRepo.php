<?php

declare(strict_types=1);

namespace App\Branch\Api\Repository;

use App\Branch\Api\BranchDTO;
use App\Branch\Api\Model\ModelAuthors;
use App\Branch\Api\Model\ModelBranch;

class BranchRepo
{
    public function __construct(
        private ModelBranch $modelBranch,
        private ModelAuthors $modelAuthors
    ){}

    public function findBranch(int $branch_id)
    {
        $params = $this->modelBranch->find($branch_id);

        return new BranchDTO($params);
    }

    public function getBranchAuthors(?int $branch_id)
    {
        return $branch_id ? $this->modelBranch->getBranchAuthors($branch_id) : [];
    }

    public function getAuthors(int $user_id, array $branch_authors, array $query_params = [])
    {
        $own_authors = $this->modelAuthors->getByUser($user_id);

        $own_authors_ids = array_map(fn($author) => $author->id, $own_authors);
        $branch_authors_ids = array_map(fn($author) => $author->author_id, $branch_authors);

        $except = array_unique(array_merge($own_authors_ids, $branch_authors_ids));

        $filter = $query_params['filter'] ?? null;
        $search = $query_params['search'] ?? null;
        $page = $query_params['page'] ?? 1;
        $limit = $query_params['limit'] ?? 10;
        $offset = ((int) $page - 1) * (int) $limit;

        $authors = $this->modelAuthors->getByFilter(
            (int) $limit,
            $offset,
            $filter,
            $search,
            $except
        );

        $authors[] = $own_authors;

        return $authors;
    }

    public function getGenres()
    {
        return $this->modelBranch->getGenres();
    }
}
