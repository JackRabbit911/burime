<?php

declare(strict_types=1);

namespace App\Api\Author\Repository;

use App\Api\Author\Model\ModelAuthorDelete;
use Sys\Helper\Facade\Dir;

class AuthorDeleteRepo
{
    private const DELETED = 0;
    private string $dir = './avatar/author/';

    public function __construct(private ModelAuthorDelete $model) {}

    public function deleteAuthor(int $author_id)
    {
        $alias = $this->model->getAlias($author_id);

        if ($this->allowDelete($author_id)) {
            Dir::clearByMask($this->dir, "$author_id.*");
            return $this->remove($author_id, $alias);
        }

        return $this->markAsDeleted($author_id, $alias);
    }

    private function allowDelete(int $author_id): bool
    {
        $count = $this->model->getCountPosts($author_id) + $this->model->getCountMembers($author_id);

        return $count === 0;
    }

    private function remove(int $author_id, string $alias)
    {
        $this->model->delete($author_id);

        return 'Author/Group "' . $alias . '" was deleted successful';
    }

    private function markAsDeleted(int $author_id, string $alias)
    {
        $this->model->setAuthorStatus($author_id, self::DELETED);

        return 'Author/Group "' . $alias . '" marked as deleted';
    }
}
