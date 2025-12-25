<?php

declare(strict_types=1);

namespace App\Api\Private\Repository;

use App\Api\Private\Model\ModelBooks;
use App\Api\Private\Model\ModelDrafts;
use App\Api\Private\StatDTO;

class StatRepo
{
    public function __construct(
        private ModelBooks $modelBooks,
        private ModelDrafts $modelDrafts
    ) {}

    public function get(int $user_id)
    {
        return new StatDTO([
            'books' => [
                'total' => $this->modelBooks->getCount($user_id),
                'own' => $this->modelBooks->getOwnCount($user_id),
            ],
            'drafts' => $this->modelDrafts->getCount($user_id),
        ]);
    }
}
