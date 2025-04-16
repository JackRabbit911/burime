<?php declare(strict_types=1);

namespace App\Burime\Component;

use App\Burime\Post;
use App\Burime\Service\PostPermissions;
use App\Burime\Component\PostControls;
use Common\Contract\BranchInterface;
use Common\Enum\PostStatus;
use Sys\Contract\UserInterface;

class CmpPost
{
    private BranchInterface $branch;
    private ?UserInterface $user;
    private PostPermissions $postPermissions;
    private PostControls $postControls;
    private Timer $timer;

    public function __construct(BranchInterface $branch, ?UserInterface $user = null)
    {
        $this->branch = $branch;
        $this->user = $user;

        $this->postPermissions = new PostPermissions($branch, $user);
        $this->postControls = new PostControls($this->postPermissions);
        $this->timer = new Timer($branch, $this->postPermissions);
    }

    public function render(Post $post)
    {
        $msg = '';

        if ($post->status === PostStatus::Draft->value && $this->user
        && $this->branch->info['current_writer'] === $this->user->id) {
            $msg = $this->branch->info['time_up'] ? 'Expired' : 'Draft';
        } elseif ($post->status === PostStatus::Moderation->value) {
            $msg = 'Under moderation';
        }

        $data = [
            'branch' => $this->branch,
            'postPermissions' => $this->postPermissions,
            'postControls' => $this->postControls,
            'timer' => $this->timer,
            'post' => $post,
            'msg' => $msg,
        ];

        return view('burime/post', $data);
    }

    public function isTimer()
    {
        return $this->postPermissions->timer();
    }
}
