<?php declare(strict_types=1);

namespace App\Branch\Model;

use App\Branch\Branch;
use Sys\Model\ModelEntity;
use Sys\Entity\Entity;

class ModelBranch extends ModelEntity
{
    protected string $table = 'branches';
    protected string $entityClass = Branch::class;

    public function save(Entity|array $branch): mixed
    {
        $id = (parent::save($branch)) ?: $branch->id;

        if (isset($branch->genres)) {
            foreach ($branch->genres as $genre_id) {
                $genres[] = [
                    'branch_id' => $id,
                    'genre_id' => $genre_id,
                ];
            }
        }

        foreach ($branch->authors as $author) {
            $authors[] = [
                'branch_id' => $id,
                'author_id' => $author->id,
                'user_id' => $author->user_id ?? null,
                'role' => $author->role,
                'status' => $author->status,
            ];
        }

        if (isset($genres)) {
            $table_bg = $this->qb->table('branches_genres');

            $table_bg->where('branch_id', '=', $id)->delete();
            $table_bg->insert($genres);
        }

        if (isset($authors) && !empty($authors)) {
            $table_ba = $this->qb->table('branches_authors');

            $table_ba->where('branch_id', '=', $id)->delete();
            $table_ba->insert($authors);
        }

        return $id;
    }
}
