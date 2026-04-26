<?php declare(strict_types=1);

namespace App\Author\Controller;

use App\Author\Model\ModelAuthor;
use App\Author\Model\ModelUserGroup;
use App\Author\Repo\Statistic;

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
        $author = $this->request->getAttribute('author') ?? $this->modelAuthor->find($id);

        if (!$author->id) {
            abort();
        }

        $this->data['author'] = $author;
        $this->data['title'] = $author->alias;
        $this->data['favorite'] = ($this->user) 
            ? $this->modelUserGroup->inUserGroup($this->user->id, $id, 100) : false;
    }

    public function __invoke(Statistic $repo, $id)
    {
        $this->data['statistic'] = $repo->get($this->data['author']);
        return view('author/tab_stat', $this->data);
    }

    public function info($id)
    {
        return view('author/tab_info', $this->data);
    }

    public function members($id)
    {
        $this->data['members'] = $this->modelAuthor->getMembers($id);
        $this->data['favorite'] = ($this->user) ? $this->modelUserGroup->inUserGroup($this->user->id, $id, 100) : false;
        $this->app->js('/assets/js/checkboxCheck.js');

        return view('author/tab_members', $this->data);
    }
}
