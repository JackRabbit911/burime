<?php declare(strict_types=1);

namespace Common\Repository;

use App\Burime\Repository\BranchRepo as BurimeBranchRepo;
use Common\Contract\BranchInterface;

class BranchRepo
{
    private BurimeBranchRepo $repo;

    public function __construct(BurimeBranchRepo $repo)
    {
        $this->repo = $repo;
    }

    public function find($id): ?BranchInterface
    {
        return $this->repo->find($id);
    }
}
