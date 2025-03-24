<?php declare(strict_types=1);

namespace App\Burime\Component;

use App\Branch\Branch;
use App\Burime\Post;
use App\Burime\Service\PostPermissions;
use App\Burime\Component\PostControls;
use Common\Enum\BranchStatus;
use Common\Enum\PostStatus;
use Auth\User;
use stdClass;

class CmpPost
{
    private Branch $branch;
    private User $user;
    private PostPermissions $postPermissions;
    private PostControls $postControls;
    private stdClass $perms;

    public function __construct(Branch $branch, User $user, stdClass $perms)
    {
        $this->branch = $branch;
        $this->user = $user;
        $this->perms = $perms;

        $this->postPermissions = new PostPermissions($branch, $user);
        $this->postControls = new PostControls($this->postPermissions);
    }

    public function render(Post $post)
    {
        $msg = '';

        if ($post->status === PostStatus::Draft->value
        && $this->branch->info['current_writer'] === $this->user->id) {

            $msg = $this->branch->info['time_up'] ? 'Expired' : 'Draft';
        } elseif ($post->status === PostStatus::Moderation->value) {
            $msg = 'Under moderation';
        }

        $data = [
            'branch' => $this->branch,
            'postPermissions' => $this->postPermissions,
            'postControls' => $this->postControls,
            'post' => $post,
            // 'perms' => $this->perms,
            'msg' => $msg,
        ];

        return view('burime/post', $data);
    }
}
