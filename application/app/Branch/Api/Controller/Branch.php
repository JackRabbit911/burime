<?php

declare(strict_types=1);

namespace App\Branch\Api\Controller;

use App\Branch\Api\BranchDTO;
use App\Branch\Api\Repository\BranchAuthorsRepo;
use App\Branch\Model\ModelGenre;
use Auth\Middleware\OAuthMiddleware;
use Auth\Middleware\AuthGuardMiddleware;

#[OAuthMiddleware]
#[AuthGuardMiddleware]
class Branch extends ApiContractController
{
    public function vocabularies(ModelGenre $model_genre)
    {
        $total_genres = $model_genre->getTitles();

        return [
            'genres' => $total_genres,
            'branch' => new BranchDTO($this->user->id),
        ];
    }

    public function authors(BranchAuthorsRepo $repo)
    {
        $query_params = $this->request->getQueryParams();
        $filter = $query_params['filter'] ?? null;
        $search = $query_params['search'] ?? null;

        return $repo->getAuthorsByFilter($this->user->id, $filter, $search);
    }
}
