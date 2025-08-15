<?php

declare(strict_types=1);

namespace App\Branch\Api\Repository;

use App\Branch\Api\Model\Authors;

class BranchAuthorsRepo
{
    public function __construct(private Authors $modelAuthors){}

    public function getAuthorsByFilter($user_id, ?string $filter = null)
    {
        $own_authors = $this->modelAuthors->getByUser($user_id);
        $exception = array_map(fn($v) => $v->id, $own_authors);

        $authors = $this->modelAuthors->getByFilter($filter, $exception);

        return [
            'ownAuthors' => $own_authors,
            'authors' => $authors,
        ];
    }
}
