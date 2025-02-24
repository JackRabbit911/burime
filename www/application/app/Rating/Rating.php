<?php declare(strict_types=1);

namespace App\Rating;

// use App\Model\ModelRating;
use Auth\User;
use HttpSoft\Response\RedirectResponse;
use Sys\Controller\BaseController;

class Rating extends BaseController
{
    private ModelRating $model;
    private ?User $user;

    private $like = 5;
    private $dislike = 2;

    public function __construct(ModelRating $model)
    {
        $this->model = $model;
    }

    protected function _before()
    {
        $this->user = $this->request->getAttribute('user');
    }

    public function like($post_id)
    {
        $this->model->setRating($this->user->id, $post_id, $this->like);
        return new RedirectResponse($this->referer());
    }

    public function dislike($post_id)
    {
        $this->model->setRating($this->user->id, $post_id, $this->dislike);
        return new RedirectResponse($this->referer());
    }

    public function remove($post_id)
    {
        $this->model->removeRating($this->user->id, $post_id);
        return new RedirectResponse($this->referer());
    }

    private function referer($default = null)
    {
        if (!$default) {
            $default = url('home');
        }

        return $this->request->getServerParams()['HTTP_REFERER'] ?? $default;
    }
}
