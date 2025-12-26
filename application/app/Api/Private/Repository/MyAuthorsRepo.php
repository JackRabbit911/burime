<?php

declare(strict_types=1);

namespace App\Api\Private\Repository;

use App\Api\Private\Model\ModelAuthors;
use App\Author\Author;

class MyAuthorsRepo
{
    public function __construct(
        private ModelAuthors $modelAuthors,
    ) {}

    public function get(int $user_id, array $ownAuthorsIds)
    {
        $authors = $this->modelAuthors->getMyGroups($user_id, $ownAuthorsIds);

        array_walk($authors, function (&$author) use ($user_id) {
            $author->owner = $author->owner === $user_id ? true : false;
            $author->avatar = Author::getAvatarById($author->id, $author->alias);
            $info = json_decode($author->info);
            $author->slogan = $info->slogan;
            $author->info = $info->info;
        });

        return $authors;
    }
}
