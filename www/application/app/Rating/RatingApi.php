<?php declare(strict_types=1);

namespace App\Rating;

use Az\Route\Route;
use HttpSoft\Response\JsonResponse;

class RatingApi extends RatingAbstract
{
    #[Route(methods: 'post')]
    public function like($post_id)
    {
        $this->model->setRating($this->user->id, $post_id, $this->like);

        $data = [
            'controls' => [
                [
                    'id' => 'like-' . $post_id,
                    'href' => path('rating', ['action' => 'remove', 'post_id' => $post_id]),
                    'fill' => true,
                ],
                [
                    'id' => 'dislike-' . $post_id,
                    'href' => path('rating', ['action' => 'dislike', 'post_id' => $post_id]),
                    'fill' => false,
                ],
            ],
            'avg' => $this->model->getPostAvgRating($post_id),
        ];

        return new JsonResponse($data);
    }

    #[Route(methods: 'post')]
    public function dislike($post_id)
    {
        $this->model->setRating($this->user->id, $post_id, $this->dislike);

        $data = [
            'controls' => [
                [
                    'id' => 'like-' . $post_id,
                    'href' => path('rating', ['action' => 'like', 'post_id' => $post_id]),
                    'fill' => false,
                ],
                [
                    'id' => 'dislike-' . $post_id,
                    'href' => path('rating', ['action' => 'remove', 'post_id' => $post_id]),
                    'fill' => true,
                ],
            ],
            'avg' => $this->model->getPostAvgRating($post_id),
        ];

        return new JsonResponse($data);
    }

    #[Route(methods: 'delete')]
    public function remove($post_id)
    {
        $this->model->removeRating($this->user->id, $post_id);
        
        $data = [
            'controls' => [
                [
                    'id' => 'like-' . $post_id,
                    'href' => path('rating', ['action' => 'like', 'post_id' => $post_id]),
                    'fill' => false,
                ],
                [
                    'id' => 'dislike-' . $post_id,
                    'href' => path('rating', ['action' => 'dislike', 'post_id' => $post_id]),
                    'fill' => false,
                ],
            ],
            'avg' => $this->model->getPostAvgRating($post_id),
        ];

        return new JsonResponse($data);
    }
}
