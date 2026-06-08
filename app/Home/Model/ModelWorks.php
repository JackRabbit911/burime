<?php

declare(strict_types=1);

namespace App\Home\Model;

use Common\Contract\BranchInterface;
use Common\Enum\BranchAuthorPermissions;
use Common\Enum\BranchAuthorStatus;
use Common\Enum\BranchRole;
use Common\Enum\BranchStatus;
use Sys\Model\Model;
use Psr\Container\ContainerInterface;
use PDO;

class ModelWorks extends Model
{
    private string $branchClass;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->branchClass = $container->get(BranchInterface::class);
    }

    public function get(?int $limit = null, int $offset = 0, array $genres = [], string $search = '')
    {
        $table = $this->qb->table('branches')
            ->select('branches.*')
            ->select($this->qb->raw('GROUP_CONCAT(DISTINCT `authors`.`alias` ORDER BY branches_authors.role DESC SEPARATOR ", ") AS alias'))
            ->select($this->qb->raw('GROUP_CONCAT(DISTINCT `genres`.`title` ORDER BY genres.weight SEPARATOR ", ") AS genreStr'))
            ->join('branches_authors', 'branches_authors.branch_id', '=', 'branches.id')
            ->join('authors', 'authors.id', '=', 'branches_authors.author_id')
            ->join('branches_genres', 'branches_genres.branch_id', '=', 'branches.id')
            ->join('genres', 'genres.id', '=', 'branches_genres.genre_id')
            ->where('branches.status', '>', BranchStatus::Publish->value)
            ->where('branches.role', '<', BranchRole::Commercial->value)
            ->where('branches_authors.role', '=', 255)
            ->groupBy('branches.id', 'authors.alias')
            ->orderBy('branches.updated', 'DESC');


        if (!empty($genres)) {
            $table->whereIn('branches_genres.genre_id', $genres);
        }

        if (!empty($search)) {
            $table->where(function ($qb) use ($search) {
                $qb->where($this->qb->raw('MATCH(branches.title) AGAINST(?)', [$search]));
                $qb->orWhere($this->qb->raw('MATCH(authors.alias) AGAINST(?)', [$search]));
            });
        }

        $total = $table->count();

        if ($limit) {
            $table->limit($limit)->offset($offset);
        }

        return [$table->asObject($this->branchClass)->get(), $total];
    }

    public function getCount(BranchRole $role = BranchRole::Commercial)
    {
        return $this->qb->table('branches')
            ->where('status', '>', BranchStatus::Publish->value)
            ->where('role', '<', $role->value)
            ->count();
    }
}
