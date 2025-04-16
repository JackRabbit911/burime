<?php declare(strict_types=1);

namespace App\Rating;

use Auth\User;
use Sys\Controller\BaseController;

abstract class RatingAbstract extends BaseController
{
    protected ModelRating $model;
    protected ?User $user;

    protected $like = 5;
    protected $dislike = 2;

    public function __construct(ModelRating $model)
    {
        $this->model = $model;
    }

    protected function _before()
    {
        $this->user = $this->request->getAttribute('user');
    }
}
