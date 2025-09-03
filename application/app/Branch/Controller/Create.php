<?php declare(strict_types=1);

namespace App\Branch\Controller;

use App\Branch\Model\ModelGenre;
use App\Branch\Component\BranchGenresForm;
use App\Branch\Component\RulesCreateForm;
use App\Branch\Service\AddAuthors;
use App\Branch\Component\AuthorsChoice;
use App\Branch\Component\CoverForm;
use App\Branch\Component\PublishForm;
use App\Branch\Middleware\AuthorGuard;
use App\Branch\Branch;
use Sys\Controller\WebController;
use Sys\Collection\Collection;

#[AuthorGuard]
class Create extends WebController
{
    private array $data;

    public function _before()
    {
        $this->data['branch'] = $this->session->keep('branch') ?? new Branch;
        $this->data['title'] = __('Create the book');
    }

    public function branch($id = null)
    {
        $this->app->js('/assets/js/branch.js');
        return view('branch/create/branch');
    }

    public function genres(ModelGenre $modelGenre)
    {
        $totalGenres = new Collection($modelGenre->getTitles());

        $this->data['step'] = 1;
        $this->data['form'] = new BranchGenresForm($totalGenres, $this->data['branch']);
        $this->data['sbmt'] = [
            'prev' => null,
            'next' => 'rules',
        ];

        return view('branch/create/form_wrapper', $this->data);
    }

    public function rules()
    {
        $this->data['step'] = 2;
        $this->data['form'] = new RulesCreateForm($this->data['branch']);
        $this->data['sbmt'] = [
            'prev' => 'genres',
            'next' => 'authors',
        ];

        return view('branch/create/form_wrapper', $this->data);
    }

    public function authors(AddAuthors $addAuthors)
    {
        $queryParams = $this->request->getQueryParams();
        $formData = $addAuthors->getFormData($queryParams, $this->user, $this->data['branch']);
        
        $this->data['step'] = 3;
        $this->data['form'] = new AuthorsChoice($formData, $this->data['branch']);
        $this->data['sbmt'] = [
            'prev' => 'rules',
            'next' => 'cover',
        ];

        return view('branch/create/form_wrapper', $this->data);
    }

    public function cover(ModelGenre $model)
    {
        $this->app->add('coverpath', trim(Branch::COVERPATH, '.'));
        $genres = $model->getTitlesByIds($this->data['branch']->genres);

        $this->data['step'] = 4;
        $this->data['form'] = new CoverForm($this->data['branch']);
        $this->data['controls'] = 'branch/create/cover_controls.twig';
        $this->data['branch']->genreStr = implode(', ', $genres);

        return view('branch/create/form_wrapper', $this->data);
    }

    public function publish()
    {
        $form = new PublishForm($this->data['branch']);
        $ready = $form->ready($this->data['branch']);
        $form->set('ready', $ready);

        $this->data['step'] = 5;
        $this->data['ready'] = $ready;
        $this->data['form'] = $form;
        $this->data['controls'] = 'branch/create/publish_controls.twig';

        return view('branch/create/form_wrapper', $this->data);
    }
}
