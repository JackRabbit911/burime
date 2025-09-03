<?php

declare(strict_types=1);

namespace App\Branch\Api\Controller;

use App\Branch\Api\BranchDTO;
use App\Branch\Api\FirstLastDTO;
use App\Branch\Api\Repository\BranchRepo;
use App\Branch\Model\ModelGenre;
use Auth\Middleware\OAuthMiddleware;
use Auth\Middleware\AuthGuardMiddleware;

#[OAuthMiddleware]
#[AuthGuardMiddleware]
class Branch extends ApiContractController
{
    public function __construct(private BranchRepo $repo){}


    public function bootstrap(?int $id = null)
    {
        $data['branch'] = $id ? $this->repo->findBranch($id) : new BranchDTO();
        $data['genres'] = $this->repo->getGenres();
        $data['posts'] = $id ? $this->repo->getFirstLastPosts($id) : new FirstLastDTO();

        return $data;
    }

    public function authors()
    {
        $query_params = $this->request->getQueryParams();

        [
            $authors_count,
            $authors,
            $own_authors,
        ] = $this->repo->getAuthors($this->user->id, $query_params);

        return [
            'authors' => $authors,
            'authorsCount' => $authors_count,
            'ownAuthors' => $own_authors,
        ];
    }

    public function vocabularies(ModelGenre $model_genre)
    {
        $total_genres = $model_genre->getTitles();

        return [
            'genres' => $total_genres,
            'branch' => new BranchDTO($this->user->id),
        ];
    }
}
