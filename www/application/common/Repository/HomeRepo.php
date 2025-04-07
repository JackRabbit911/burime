<?php declare(strict_types=1);

namespace Common\Repository;

use App\Home\Model\ModelAuthors;
use App\Home\Model\ModelWorks;

class HomeRepo
{
    public function __construct(
        private ModelWorks $modelWorks,
        private ModelAuthors $modelAuthor
    ) {}

    public function getBranchesCount()
    {
        return $this->modelWorks->getCount();
    }

    public function getBranches($limit)
    {
        return $this->modelWorks->get($limit);
    }

    public function getAuthorsCount()
    {
        return $this->modelAuthor->getCount();
    }

    public function getAuthors($limit)
    {
        return $this->modelAuthor->get($limit);
    }
}
