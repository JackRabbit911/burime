<?php declare(strict_types=1);

namespace App\Rating;

use HttpSoft\Response\RedirectResponse;

class Rating extends RatingAbstract
{
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
