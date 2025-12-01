<?php

declare(strict_types=1);

namespace App\Branch\Api\Controller;

use App\Branch\Api\Middleware\OwnerBranchGuard;
use App\Branch\Api\Repository\BranchRepo;
use App\Branch\Api\Middleware\AuthGuard;
use App\Branch\Api\Middleware\AuthorsSearchFilterValidation;
use Auth\Middleware\OAuthMiddleware;
use Az\Route\Route;
use Common\Enum\BranchAuthorPermissions;
use Common\Enum\BranchAuthorStatus;

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
        $data['files'] = $this->repo->getBase64CoverFiles($id);
        $data['ownAuthors'] = $this->repo->getOwnAuthors($this->user->id);
        $data['authorsFilters'] = $this->repo->getAuthorsFilters();
        $data['authorsPermissions'] = BranchAuthorPermissions::getArray();
        $data['authorsStatuses'] = BranchAuthorStatus::getArray();

        return $data;
    }

    #[AuthorsSearchFilterValidation]
    public function getauthors()
    {
        $query_params = $this->request->getQueryParams();

        [$authors_count, $authors_list] = $this->repo->getAuthors($this->user->id, $query_params);

        return [
            'list' => $authors_list,
            'count' => $authors_count,
        ];
    }

    #[Route(methods: 'post')]
    public function gettranslate()
    {
        $json = $this->request->getBody()->getContents();
        $data = json_decode($json);
       
        return $this->i18n->getMap($data->filter);
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
