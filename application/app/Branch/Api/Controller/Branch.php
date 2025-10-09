<?php

declare(strict_types=1);

namespace App\Branch\Api\Controller;

use App\Branch\Api\Middleware\OwnerBranchGuard;
use App\Branch\Api\Repository\BranchRepo;
use Auth\Middleware\OAuthMiddleware;
use App\Branch\Api\Middleware\AuthGuard;

#[OAuthMiddleware]
#[AuthGuard]
#[OwnerBranchGuard]
class Branch extends ApiContractController
{
    public function __construct(private BranchRepo $repo){}


    public function bootstrap(?int $id = null)
    {
        $data['branch'] = $this->request->getAttribute('branch') ?? $this->repo->findBranch($id);
        $data['genres'] = $this->repo->getGenres();
        $data['posts'] = $this->repo->getFirstLastPosts($id);
        $data['files'] = $this->repo->getCoverFiles($id);

        return $data;
    }

    public function getbootstrap(?int $id = null)
    {
        $data['branch'] = $this->request->getAttribute('branch') ?? $this->repo->findBranch($id);
        $data['genres'] = $this->repo->getTotalGenres();
        $data['posts'] = $this->repo->getFirstLastPosts($id);
        $data['files'] = $this->repo->getCoverFiles($id);

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
}
