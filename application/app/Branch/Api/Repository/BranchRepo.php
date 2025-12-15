<?php

declare(strict_types=1);

namespace App\Branch\Api\Repository;

use App\Branch\Api\BranchDTO;
use App\Branch\Api\FirstLastDTO;
use App\Burime\Model\ModelPost;

class BranchRepo extends ParentRepo
{
    protected string $prefix = './img/branch/';

    public function get(int|string|null $id)
    {
        $data['branch'] = $this->findBranch((int) $id);

        if (!$data['branch']) {
            return null;
        }

        $data['branch_genres'] = $this->getBranchGenres((int) $id);
        $data['members'] = $this->getBranchAuthors((int) $id);
        $data['posts'] = $this->getFirstLastPosts((int) $id);

        return $data;
    }

    public function findBranch(?int $branch_id)
    {
        $params = $branch_id ? $this->modelBranch->find($branch_id) : [];

        return !is_null($params) ? new BranchDTO($params) : null;
    }

    public function getBranchGenres(?int $branch_id)
    {
        return $branch_id ? $this->modelBranch->getBranchGenres($branch_id) : [];
    }

    public function getBranchAuthors(?int $branch_id)
    {
        return $branch_id ? $this->modelBranch->getBranchAuthors($branch_id) : [];
    }

    public function getGenres()
    {
        return $this->modelBranch->getGenres();
    }

    public function getFirstLastPosts(?int $branch_id)
    {
        $params = $branch_id ? [
            'first' => $this->modelBranch->findPostByWeight($branch_id, 1),
            'last' => $this->modelBranch->findPostByWeight($branch_id, ModelPost::MAX_WEIGHT),
        ] : [];

        return new FirstLastDTO($params);
    }
}
