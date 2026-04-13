<?php declare(strict_types=1);

namespace App\Rating\Component;

use Common\Contract\BranchInterface;
use Common\Contract\PostInterface;
use Sys\Template\Component;
use Sys\Contract\DtoInterface;

class CmpRating extends Component
{
    private BranchInterface|DtoInterface $branch;

    public function __construct(BranchInterface|DtoInterface $branch)
    {
        $this->branch = $branch;
    }

    public function render(PostInterface|DtoInterface $post)
    {
        $data = [
            'branch' => $this->branch,
            'post' => $post,
            'link_like' => ($post->user_rating === 5)
                ? path('rating', ['action' => 'remove', 'post_id' => $post->id])
                : path('rating', ['action' => 'like', 'post_id' => $post->id]),
            'link_dislike' => ($post->user_rating === 2)
                ? path('rating', ['action' => 'remove', 'post_id' => $post->id])
                : path('rating', ['action' => 'dislike', 'post_id' => $post->id]),
        ];

        return view('rating/rating', $data);
    }
}
