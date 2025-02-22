<?php declare(strict_types=1);

namespace App\Burime\Controller;

use App\Burime\Component\BranchAuthors;
use App\Burime\Component\PostControls;
use App\Burime\Component\PostForm;
use App\Burime\Component\Ask2Join;
use App\Burime\Service\PostPermissions;
use App\Burime\Service\BranchPermissions;
use App\Burime\Middleware\AuthorPostGuard;
use App\Burime\Model\FindBranch;
use Common\Contract\BranchInterface;
use Common\Enum\PostStatus;
use Common\Enum\BranchStatus;
use Common\Repository\PostListRepo;
use Az\Route\Route;
use Sys\Controller\WebController;
use Sys\Paginator;
use Sys\Collection\Collection;

class Branch extends WebController
{
    private FindBranch $model;
    private ?BranchInterface $branch;
    private array $data;
    private $paginationView = 'web/common/pagination';
    private $limit = 40;

    public function __construct(FindBranch $model)
    {
        $this->model = $model;
    }

    protected function _before()
    {
        $route = $this->request->getAttribute(Route::class);
        $branch_id = $route->getParameters()['branch_id'];

        $this->branch = ($this->request->getAttribute('branch')) 
            ?: $this->model->find($branch_id);

        $this->data = $this->getData();

        if (!$this->branch || !$this->data['perms']->show) {
            abort();
        }
    }

    public function __invoke(PostListRepo $repo, $branch_id)
    {
        $data = $this->getData();

        $currentPage = $this->request->getQueryParams()['page'] ?? 1;
        $data += $repo->get($this->user->id ?? null, (int) $branch_id, $currentPage, $this->limit);
        $data['paginator'] = new Paginator($this->request, $data['count'], $this->limit, $this->paginationView);

        $this->branch->maxWeight = $data['max_weight'];

        if (!$data['posts']->empty() 
            && $data['posts']->last()->status === PostStatus::Draft->value
            && $this->branch->info['time_up'] 
            && $this->branch->info['current_writer'] !== $this->user->id) {
                $data['posts'] = $data['posts']->where('status', '>', PostStatus::Draft->value);
                --$this->branch->maxWeight;
        };

        $postPermissions = new PostPermissions($this->branch, $this->user);

        $controls = new PostControls($postPermissions);
        $this->app->add('PostControls', $controls);        
        $this->app->add('postPermissions', $postPermissions);

        $data['main'] = view('web/branch/posts', $data);
        return view('web/common/home', $data);
    }

    public function authors($branch_id)
    {
        $data = $this->getData();
        $data['main'] = (new BranchAuthors($data['branch']))->render();

        return view('web/common/home', $data);
    }

    public function rules($branch_id)
    {
        $data = $this->getData();
        $data['main'] = view('web/branch/rules', $data);

        return view('web/common/home', $data);
    }

    public function ask2join($branch_id)
    {
        $data = $this->getData();
        $data['main'] = (new Ask2Join($this->branch->id, $this->user))->render();
        $data['is_ask'] = true;

        return view('web/common/home', $data);
    }

    #[AuthorPostGuard]
    public function form($post_id = null)
    {
        $post_last = $this->request->getAttribute('last');
        $post_current = $this->request->getAttribute('current');

        $data = $this->getData();

        if (!$this->branch->info['time_up']) {
            $this->branch->status = BranchStatus::Blocked->value;
        }

        if ($this->branch->info['current_writer'] ?? 0 !== $this->user->id) {
            $this->branch->info['time_beguin'] = time();
            $this->branch->info['current_writer'] = $this->user->id;
        }
        
        $this->branch->save();

        $data['main'] = new PostForm($this->branch, $post_last, $post_current, $data['myAuthor']);

        return view('web/common/home', $data);
    }

    private function getData()
    {
        $myAuthor = $this->getBrunchAuthorsByUser($this->branch, $this->user);
        $author = ($myAuthor instanceof Collection) ? null : $myAuthor;

        $branchPerms = new BranchPermissions($this->branch, $this->user, $author);

        $data['perms'] = $branchPerms->getPerms();
        $data['branch'] = $this->branch;
        $data['sidebar'] = 'web/branch/sidebar';
        $data['myAuthor'] = $myAuthor;
        $data['title'] = $this->branch->title;

        return $data;
    }

    private function getBrunchAuthorsByUser($branch, $user)
    {
        if (!$user) {
            return null;
        }

        $branchUsersAuthor = $branch->authors->getInstance($user->id, 'user_id')
            ?: $branch->authors->getInstance($user->id, 'owner');

        // if (!$branchUsersAuthor) {
        //     return ($user->ownAuthors->count() === 1) ? $user->ownAuthors->first() : $user->ownAuthors;
        // }

        return $branchUsersAuthor;
    }
}
