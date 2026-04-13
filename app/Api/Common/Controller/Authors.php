<?php

declare(strict_types=1);

namespace App\Api\Common\Controller;

use App\Api\Common\Model\ModelAuthors;
use App\Api\Common\Repository\AuthorsRepo;

class Authors extends ApiContractController
{
    public function group(ModelAuthors $model, ?int $id)
    {
        return $model->getByGroup($id);
    }

    public function ownauthors(ModelAuthors $model)
    {
        return $model->getOwnAuthors($this->user->id);
    }

    public function authors(AuthorsRepo $repo)
    {
        $query_params = $this->request->getQueryParams();
        [$authors_count, $authors_list] = $repo->getAuthors($this->user->id, $query_params);

        return [
            'list' => $authors_list,
            'count' => $authors_count,
        ];
    }
}
