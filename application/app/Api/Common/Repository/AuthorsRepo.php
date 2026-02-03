<?php

declare(strict_types=1);

namespace App\Api\Common\Repository;

use App\Api\Common\Model\ModelAuthors;

class AuthorsRepo
{
    public function __construct(
        private ModelAuthors $modelAuthors
    ) {}

    public function getAuthors(int $user_id, array $query_params = [])
    {   
        $filter = $query_params['filter'] ?? null;
        $search = $query_params['search'] ?? null;
        $page = $query_params['page'] ?? 1;
        $limit = $query_params['limit'] ?? 25;
        $offset = ((int) $page - 1) * (int) $limit;
        
        if ($filter !== 'groups') {
            $own_authors = $this->modelAuthors->getOwnAuthors($user_id);
            $except = array_map(fn($author) => $author->id, $own_authors);
        } else {
            $except = [];
        }

        $authors = $this->modelAuthors->getByFilter(
            $user_id,
            (int) $limit,
            $offset,
            $filter,
            $search,
            $except
        );

        return $authors;
    }
}
