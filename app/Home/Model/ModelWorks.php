<?php

declare(strict_types=1);

namespace App\Home\Model;

use Common\Contract\BranchInterface;
use Common\Enum\AuthorRole;
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
            ->leftJoin('branches_authors', 'branches_authors.branch_id', '=', 'branches.id')
            ->leftJoin('authors', 'authors.id', '=', 'branches_authors.author_id')
            ->leftjoin('branches_genres', 'branches_genres.branch_id', '=', 'branches.id')
            ->leftjoin('genres', 'genres.id', '=', 'branches_genres.genre_id')
            ->where('branches.status', '>', BranchStatus::Publish->value)
            ->where('branches.role', '<', BranchRole::Commercial->value)
            ->where('branches_authors.role', '=', 255)
            ->where('genres.weight', '>', 0)
            ->groupBy('branches.id', 'authors.alias')
            ->orderBy('branches.updated', 'DESC');

        if (!empty($genres) || !empty($search)) {
            $filter_genres = $this->filterGenres($genres);

            if ($filter_genres === []) {
                return [[], 0];
            }

            $filter = $this->filterSearch($search, $filter_genres);

            if (!empty($filter)) {
                $table->whereIn('branches.id', $filter);
            } else {
                return [[], 0];
            }
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

    private function filterGenres(array $genres): array|false
    {
        if (!empty($genres)) {
            return $this->qb->table('branches_genres')
                ->selectDistinct('branch_id')
                ->join('branches', 'branches.id', '=', 'branch_id')
                ->where('branches.status', '>', BranchStatus::Publish->value)
                ->where('branches.role', '<', BranchRole::Commercial->value)
                ->whereIn('genre_id', $genres)
                ->setFetchMode(PDO::FETCH_COLUMN)
                ->get();
        }

        return false;
    }

    private function filterSearch(string $search, array|false $filter_genres): array
    {
        $table = $this->qb->table('branches')
            ->select('branches.id')
            ->join('branches_authors', 'branches_authors.branch_id', '=', 'branches.id')
            ->join('authors', 'authors.id', '=', 'branches_authors.author_id')
            ->where('branches_authors.status', '>=', BranchAuthorStatus::member->value);

        if ($filter_genres) {
            $table->whereIn('branches.id', $filter_genres);
        }

        if (!empty($search)) {
            $table->where(function($qb) use ($search) {
                $qb->where($this->qb->raw('MATCH(title) AGAINST(?)', [$search]));
                $qb->orWhere($this->qb->raw('MATCH(alias) AGAINST(?)', [$search]));
            });
        }

        return $table->setFetchMode(PDO::FETCH_COLUMN)
            ->get();
    }
}
