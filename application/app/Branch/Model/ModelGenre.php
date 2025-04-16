<?php declare(strict_types=1);

namespace App\Branch\Model;

use Sys\Model\Trait\QueryBuilder;
use Sys\Model\Trait\Save;

final class ModelGenre 
{
    use QueryBuilder;
    use Save;
    
    private string $table = 'genres';

    public function getTitles()
    {
        return $this->qb->table($this->table)
            ->select('id', 'title', 'weight')
            ->get();
    }

    public function find($id = null)
    {
        if (!$id) {
            return null;
        }

        return $this->qb->table($this->table)
            ->find($id);
    }

    public function getTitlesByIds($ids)
    {
        return $this->qb->table($this->table)
            ->select('title')
            ->where('weight', '>', 0)
            ->whereIn('id', $ids)
            ->setFetchMode(\PDO::FETCH_COLUMN)
            ->get();
    }

    public function getByBranch($branch_id)
    {
        return $this->qb->table('genres')
            ->select('genres.id', 'genres.title', 'genres.weight')
            ->join('branches_genres', 'branches_genres.genre_id', '=', 'genres.id')
            ->where('branches_genres.branch_id', '=', $branch_id)
            ->get();
    }
}
