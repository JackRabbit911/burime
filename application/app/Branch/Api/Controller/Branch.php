<?php

declare(strict_types=1);

namespace App\Branch\Api\Controller;

use App\Branch\Api\Middleware\OwnerBranchGuard;
use App\Branch\Api\Middleware\DraftBranchGetter;
use App\Branch\Api\Middleware\AuthorsSearchFilterValidation;
use App\Branch\Api\Middleware\AuthGuard;
use App\Branch\Api\Repository\BranchRepo;
use App\Branch\Api\Repository\HelpRepo;
use Auth\Middleware\OAuthMiddleware;
use Common\Enum\BranchAuthorPermissions;
use Common\Enum\BranchAuthorStatus;
use Az\Route\Route;
use Common\Enum\MemberRole;

#[OAuthMiddleware]
#[AuthGuard]
class Branch extends ApiContractController
{
    #[Route(tokens: ['id' => '\d*', 'draft' => 'draft|'])]
    #[DraftBranchGetter]
    #[OwnerBranchGuard]
    public function getbootstrap(?int $id = null, ?string $draft = null)
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

    #[AuthorsSearchFilterValidation]
    public function getauthors(BranchRepo $repo)
    {
        $query_params = $this->request->getQueryParams();

        [$authors_count, $authors_list] = $repo->getAuthors($this->user->id, $query_params);

        return [
            'list' => $authors_list,
            'count' => $authors_count,
        ];
    }

    public function gethelp(HelpRepo $repo, int $step)
    {
        $help = $repo->getHelp($step);

        return $help ? $help : $this->_error('Not found', 404);
    }

    #[Route(methods: 'post')]
    public function gettranslate()
    {
        $json = $this->request->getBody()->getContents();
        $data = json_decode($json);

        return $this->i18n->getMap($data->filter);
    }
}
