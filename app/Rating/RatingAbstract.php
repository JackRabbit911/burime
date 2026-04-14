<?php declare(strict_types=1);

namespace App\Rating;

use Sys\Controller\ApiController;

abstract class RatingAbstract extends ApiController
{
    protected ModelRating $model;
    protected $user;

    protected $like = 5;
    protected $dislike = 2;

    public function __construct(ModelRating $model)
    {
        $this->model = $model;
    }
}
