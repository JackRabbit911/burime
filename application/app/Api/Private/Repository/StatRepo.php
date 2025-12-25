<?php

declare(strict_types=1);

namespace App\Api\Private\Repository;

use App\Api\Private\Model\ModelAuthors;
use App\Api\Private\Model\ModelBooks;
use App\Api\Private\Model\ModelDrafts;
use App\Api\Private\StatDTO;

class StatRepo
{
    public function __construct(
        private ModelAuthors $modelAuthors,
        private ModelBooks $modelBooks,
        private ModelDrafts $modelDrafts
    ) {}

    public function get(int $user_id, array $ownAuthorsIds)
    {
        return new StatDTO([
            'books' => [
                'total' => $this->modelBooks->getCount($ownAuthorsIds),
                'own' => $this->modelBooks->getOwnCount($user_id, $ownAuthorsIds),
            ],
            'drafts' => $this->modelDrafts->getCount($user_id),
            'authors' => [
                'total' => $this->modelAuthors->getGroupsCount($ownAuthorsIds),
                'own' => count($ownAuthorsIds),
            ],
        ]);
    }
}
