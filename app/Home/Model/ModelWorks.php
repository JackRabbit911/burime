<?php declare(strict_types=1);

namespace App\Home\Model;

use Common\Contract\BranchInterface;
use Common\Enum\AuthorRole;
use Common\Enum\BranchRole;
use Common\Enum\BranchStatus;
use Sys\Model\Model;
use Psr\Container\ContainerInterface;

class ModelWorks extends Model
{
    private string $branchClass;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->branchClass = $container->get(BranchInterface::class);
    }

    public function get($limit = null, $offset = 0)
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
            ->where('branches_authors.role', '>=', AuthorRole::Master->value)
            ->where('genres.weight', '>', 0)
            ->groupBy('branches.id', 'authors.alias')
            ->orderBy('branches.updated', 'DESC');
            
        if ($limit) {
            $table->limit($limit)->offset($offset);
        }

        return $table->asObject($this->branchClass)->get();
    }

    public function getCount(BranchRole $role = BranchRole::Commercial)
    {
        return $this->qb->table('branches')
            ->where('status', '>', BranchStatus::Publish->value)
            ->where('role', '<', $role->value)
            ->count();
    }
}
