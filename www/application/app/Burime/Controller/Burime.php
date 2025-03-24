<?php declare(strict_types=1);

namespace App\Burime\Controller;

use App\Burime\Component\PostControls;
use App\Burime\Component\Ask2Join;
use App\Burime\Component\CmpPost;
use App\Burime\Component\PostForm;
use App\Burime\Middleware\AuthorPostGuard;
use App\Burime\Middleware\TimeUpMiddleware;
use App\Burime\Repository\BranchRepo;
use App\Burime\Repository\PostsRepo;
use App\Burime\Service\BranchPermissions;
use App\Burime\Service\PostPermissions;
use Common\Enum\AuthorRole;
use Common\Enum\BranchAuthorStatus;
use Common\Enum\BranchStatus;
use Sys\Controller\WebController;
use Az\Route\Route;

#[TimeUpMiddleware]
class Burime extends WebController
{
    private BranchRepo $repo;
    private array $data;
    private string $sidebar = 'burime/sidebar';

    public function __construct(BranchRepo $repo)
    {
        $this->repo = $repo;
    }

    protected function _before()
    {
        $route = $this->request->getAttribute(Route::class);
        $branch_id = $route->getParameters()['branch_id'];

        $this->data['branch'] = ($this->request->getAttribute('branch')) 
            ?: $this->repo->find($branch_id);

        $this->data['sidebar'] = $this->sidebar;
        $author = $this->getBrunchAuthorsByUser($this->data['branch'], $this->user);
        $branchPerms = new BranchPermissions($this->data['branch'], $this->user, $author);
        $this->data['perms'] = $branchPerms->getPerms();
        $this->data['myAuthor'] = $author;
    }

    public function __invoke(PostsRepo $repo, $branch_id)
    {
        $this->data['main'] = 'burime/posts';
        $this->data['posts'] = $repo->getPosts((int) $branch_id, $this->user?->id);
        $this->data['last'] = $repo->getLastPost($this->data['posts']);

        $this->app->add('CmpPost', new CmpPost($this->data['branch'], $this->user, $this->data['perms']));

        if ($this->user) {
            $this->app->js('/assets/js/rating.js');
        }

        // if ($this->data['perms']->timer) {
        //     $this->app->js('/assets/js/timer.js');
        // }

        return view('burime/posts', $this->data);
    }

    public function authors()
    {
        $this->data['authors'] = $this->data['branch']->authors->map(function ($v) {
            $v->role = AuthorRole::getRoleString($v->role);
            $v->status = BranchAuthorStatus::getStatusString($v->status);
            return $v;
        });

        return view('burime/authors', $this->data);
    }

    public function rules()
    {
        return view('burime/rules', $this->data);
    }

    public function ask2join($branch_id)
    {
        $this->data['form'] = (new Ask2Join($branch_id, $this->user))->render();
        return view('burime/form_wrapper', $this->data);
    }

    #[AuthorPostGuard]
    public function form()
    {
        $post_last = $this->request->getAttribute('last');
        $post_current = $this->request->getAttribute('current');

        if (!$this->data['branch']->info['time_up']) {
            $this->data['branch']->status = BranchStatus::Blocked->value;
        }

        if (!isset($this->data['branch']->info['current_writer'])
        || $this->data['branch']->info['current_writer'] !== $this->user->id) {
            $this->data['branch']->info['time_beguin'] = time();
            $this->data['branch']->info['current_writer'] = $this->user->id;
        }
        
        $this->data['branch']->save();

        $this->data['myAuthors'] = ($this->data['myAuthor']) ?: $this->user->ownAuthors;
        $this->data['form'] = new PostForm($this->data, $post_last, $post_current);

        return view('burime/form_wrapper', $this->data);
    }

    private function getBrunchAuthorsByUser($branch, $user)
    {
        if (!$user) {
            return null;
        }

        return $branch->authors->getInstance($user->id, 'user_id')
            ?: $branch->authors->getInstance($user->id, 'owner');
    }
}
