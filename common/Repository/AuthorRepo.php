<?php declare(strict_types=1);

namespace Common\Repository;

use App\Author\Author;
use App\Author\Model\ModelAuthor;

class AuthorRepo
{
    private ModelAuthor $modelAuthor;

    public function __construct(ModelAuthor $modelAuthor)
    {
        $this->modelAuthor = $modelAuthor;
    }

    public function findAuthor($author_id)
    {
        return $this->modelAuthor->find($author_id);
    }

    public function authorAvatar($author_id, $alt = '')
    {
        return Author::getAvatarById($author_id, $alt);
    }
}
