<?php

declare(strict_types=1);

namespace App\Branch\Api;

use App\Branch\Model\ModelGenre;
use Sys\Controller\ApiController;

class Create extends ApiController
{
    public function vocabularies(ModelGenre $model_genre)
    {
        $total_genres = $model_genre->getTitles();

        return [
            'success' => true,
            'result' => [
                'genres' => $total_genres,
            ],
        ];
    }


}
