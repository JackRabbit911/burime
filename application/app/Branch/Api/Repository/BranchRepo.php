<?php

declare(strict_types=1);

namespace App\Branch\Api\Repository;

use App\Branch\Api\BranchDTO;
use App\Branch\Api\FirstLastDTO;
use App\Branch\Api\Model\ModelAuthors;
use App\Branch\Api\Model\ModelBranch;
use App\Burime\Model\ModelPost;

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

    public function getAuthors(int $user_id, array $query_params = [])
    {
        $own_authors = $this->modelAuthors->getByUser($user_id);
        $except = array_map(fn($author) => $author->id, $own_authors);

        $filter = $query_params['filter'] ?? null;
        $search = $query_params['search'] ?? null;
        $page = $query_params['page'] ?? 1;
        $limit = $query_params['limit'] ?? 25;
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

    public function getFirstLastPosts($branch_id)
    {
        $params = [
            'first' => $this->modelBranch->findPostByWeight($branch_id, 1),
            'last' => $this->modelBranch->findPostByWeight($branch_id, ModelPost::MAX_WEIGHT),
        ];

        return new FirstLastDTO($params);
    }
}
