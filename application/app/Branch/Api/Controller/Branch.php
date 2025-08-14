<?php

declare(strict_types=1);

namespace App\Branch\Api;

use App\Branch\Model\ModelGenre;
use Auth\Middleware\OAuthMiddleware;
use Auth\Middleware\AuthGuardMiddleware;
use Sys\Controller\ApiController;

#[OAuthMiddleware]
#[AuthGuardMiddleware]
class Create extends ApiController
{
    public function vocabularies(ModelGenre $model_genre)
    {
        $total_genres = $model_genre->getTitles();

        return [
            'success' => true,
            'result' => [
                'genres' => $total_genres,
                'branch' => new BranchDTO($this->user->id),
            ],
        ];
    }
}
