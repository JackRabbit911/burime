<?php declare(strict_types=1);

namespace App\Author\Controller;

use App\Author\Model\ModelAuthor;
use App\Author\Model\ModelUserGroup;
use App\Author\Repo\Statistic;
// use App\Model\ModelStat;

use Sys\Controller\WebController;
use Az\Route\Route;

class Author extends WebController
{
    private ModelAuthor $modelAuthor;
    private ModelUserGroup $modelUserGroup;
    private array $data;

    public function __construct(ModelAuthor $model, ModelUserGroup $modelUserGroup)
    {
        $this->modelAuthor = $model;
        $this->modelUserGroup = $modelUserGroup;
    }

    protected function _before()
    {
        $id = $this->request->getAttribute(Route::class)->getParameters()['id'];
        $this->data['author'] = $this->request->getAttribute('author') ?? $this->modelAuthor->find($id);

        if (!$this->data['author']->id) {
            abort();
        }

        $this->data['favorite'] = ($this->user) 
            ? $this->modelUserGroup->inUserGroup($this->user->id, $id, 100) : false;
    }

    public function __invoke(Statistic $repo, $id)
    {
        $data = $this->data;
        // $data['avg_rating'] = $modelStat->getAuthorRatingAvg($id);

        $data['avg_rating'] = 4.1; //= RatingRepo->getRatingByAuthor($id);
        $data['statistic'] = $repo->get($id);

        return view('author/tab_stat', $data);
    }

    public function info($id)
    {
        $data = $this->data;
        return view('author/tab_info', $data);
    }

    public function members($id)
    {
        $data = $this->data;
        $data['members'] = $this->modelAuthor->getMembers($id);
        $data['favorite'] = ($this->user) ? $this->modelUserGroup->inUserGroup($this->user->id, $id, 100) : false;
        $this->app->js('/assets/js/checkboxCheck.js');

        return view('web/author/tab_members', $data);
    }
}
