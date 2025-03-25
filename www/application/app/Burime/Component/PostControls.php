<?php declare(strict_types=1);

namespace App\Burime\Component;

use App\Burime\Service\PostPermissions;

class PostControls
{
    private PostPermissions $permissions;

    public function __construct(PostPermissions $permissions)
    {
        $this->permissions = $permissions;
    }

    public function render($post)
    {
        $data = [
            'allow_write' => $this->permissions->write($post),
            'link_write' => path('branch', ['branch_id' => $this->permissions->branch->id, 'action' => 'form']),
            'allow_edit' => $this->permissions->edit($post),
            'link_edit' => path('branch', ['branch_id' => $this->permissions->branch->id, 'action' => 'form', 'post_id' => $post->id]),
            'allow_comment' => $this->permissions->comment($post),
            'allow_rating' => $this->permissions->rating($post),
            'link_like' => ($post->user_rating === 5)
                ? path('rating', ['action' => 'remove', 'post_id' => $post->id])
                : path('rating', ['action' => 'like', 'post_id' => $post->id]),
            'link_dislike' => ($post->user_rating === 2)
                ? path('rating', ['action' => 'remove', 'post_id' => $post->id])
                : path('rating', ['action' => 'dislike', 'post_id' => $post->id]),
            'allow_branch' => $this->permissions->branch($post),
            'allow_delete' => $this->permissions->delete($post),
            'link_delete' => path('post', ['branch_id' => $this->permissions->branch->id, 'action' => 'delete', 'post_id' => $post->id]),
            'allow_approve' => $this->permissions->approve($post),
            'link_approve' => path('post', ['branch_id' => $this->permissions->branch->id, 'action' => 'approve', 'post_id' => $post->id]),
            'link_rewrite' => path('message', ['action' => 'form', 'author_id' => $this->permissions->getPostAuthor($post)]),
            'action' => path('branch.post', ['branch_id' => $this->permissions->branch->id, 'action' => 'rating', 'post_id' => $post->id]),
            'form_id' => 'rating-' . $post->id,
            'post' => $post,
            'branch' => $this->permissions->branch,
        ];

        return view('burime/post_controls', $data);
    }
}
