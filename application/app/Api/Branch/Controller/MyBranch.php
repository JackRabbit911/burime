<?php

declare(strict_types=1);

namespace App\Api\Branch\Controller;

use App\Api\Common\Controller\ApiContractController;
use App\Api\Branch\Middleware\DraftBranchGetter;
use App\Api\Branch\Repository\BranchRepo;
use Common\Enum\BranchAuthorPermissions;
use Common\Enum\BranchAuthorStatus;
use Common\Enum\MemberRole;
use Az\Route\Route;

#[Route(tokens: ['id' => '\d*', 'draft' => 'draft|'])]
#[DraftBranchGetter]
class MyBranch extends ApiContractController
{

    public function bootstrap(?int $id = null)
    {
        $repo = $this->request->getAttribute('repo');
        $data = $this->request->getAttribute('branch');
        $data['files'] = $repo->getBase64CoverFiles($id);
        $data['total_genres'] = $repo->getTotalGenres();
        $data['ownAuthors'] = $repo->getOwnAuthors($this->user->id);
        $data['authorsFilters'] = MemberRole::getFilters();
        $data['authorsPermissions'] = BranchAuthorPermissions::getArray();
        $data['authorsStatuses'] = BranchAuthorStatus::getArray();

        return $data;
    }

    public function authors(BranchRepo $repo)
    {
        $query_params = $this->request->getQueryParams();

        [$authors_count, $authors_list] = $repo->getAuthors($this->user->id, $query_params);

        return [
            'list' => $authors_list,
            'count' => $authors_count,
        ];
    }
}
