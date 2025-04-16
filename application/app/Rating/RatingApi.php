<?php declare(strict_types=1);

namespace App\Rating;

use App\Rating\Component\CmpRating;
use Az\Route\Route;
use HttpSoft\Response\JsonResponse;
use Sys\Contract\DtoInterface;

class RatingApi extends RatingAbstract
{
    private function dtoBranch($id, $rating)
    {
        return new class($id, $rating) implements DtoInterface
        {
            public function __construct(
                public readonly int $id,
                public readonly float $rating
            ) {}
        };
    }

    private function dtoPost($id, $rating, $user_rating)
    {
        return new class($id, $rating, $user_rating) implements DtoInterface
        {
            public readonly int $id;
            public readonly float $rating;
            public readonly ?int $user_rating;

            public function __construct($id, $rating, $user_rating)
            {
                $this->id = $id;
                $this->rating = $rating;
                $this->user_rating = $user_rating;
            }
        };
    }

    private function getData(int $post_id)
    {
        $branch_id = (int) $this->request->getBody()->getContents();

        $post_rating = $this->model->getPostAvgRating($post_id);
        $user_rating = $this->model->getPostRatingByUser($post_id, $this->user->id);
        $post = $this->dtoPost($post_id, $post_rating, $user_rating);

        $branch_rating = $this->model->getBranchAwgRating($branch_id);
        $branch = $this->dtoBranch($branch_id, $branch_rating);

        return [
            [
                'id' => 'post-rating-' . $post_id,
                'html' => (new CmpRating($branch))->render($post),
            ],
            [
                'id' => 'avg-' . $post_id,
                'html' => $post->rating,
            ],
            [
                'id' => 'branch-rating',
                'html' => $branch->rating,
            ],
        ];
    }

    #[Route(methods: ['post', 'get'])]
    public function like(int $post_id)
    {
        $this->model->setRating($this->user->id, $post_id, $this->like);
        $data = $this->getData($post_id);
        return new JsonResponse($data);
    }

    #[Route(methods: 'post')]
    public function dislike(int $post_id)
    {
        $this->model->setRating($this->user->id, $post_id, $this->dislike);
        $data = $this->getData($post_id);
        return new JsonResponse($data);
    }

    #[Route(methods: ['delete', 'get'])]
    public function remove(int $post_id)
    {
        $this->model->removeRating($this->user->id, $post_id);
        $data = $this->getData($post_id);
        return new JsonResponse($data);
    }
}
