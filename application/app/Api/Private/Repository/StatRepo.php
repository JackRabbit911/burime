<?php

declare(strict_types=1);

namespace App\Api\Private\Repository;

use App\Api\Private\Model\ModelAuthors;
use App\Api\Private\Model\ModelBooks;
use App\Api\Private\Model\ModelDrafts;
use App\Api\Private\StatDTO;
use Common\Model\ModelUserStat;

class StatRepo
{
    public function __construct(
        private ModelAuthors $modelAuthors,
        private ModelBooks $modelBooks,
        private ModelDrafts $modelDrafts,
        private ModelUserStat $modelUserStat,
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
            'messages' => $this->modelUserStat->getMsgCount($ownAuthorsIds),
            'complete' => $this->getComplete($user_id),
        ]);
    }

    private function getComplete(int $user_id)
    {
        $path = config('user', 'avatar_path') . $user_id;
        $pattern = $path . '.{jpg,jpeg,png,gif}';
        $file = glob($pattern, GLOB_BRACE)[0] ?? null;
        $avatar_complete = $file ? 15 : 0;

        return $this->modelUserStat->getUserComplete($user_id) + $avatar_complete;
    }
}
