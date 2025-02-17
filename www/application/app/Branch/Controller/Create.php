<?php declare(strict_types=1);

namespace App\Branch\Controller;

use App\Branch\Model\ModelGenre;
use App\Branch\Component\BranchGenresForm;
use App\Branch\Component\RulesCreateForm;
use App\Branch\Service\AddAuthors;
use App\Branch\Component\AuthorsChoice;
use App\Branch\Component\CoverForm;
use App\Branch\Component\PublishForm;
use App\Branch\Branch;
use Sys\Controller\WebController;
use Sys\Collection\Collection;
// use App\Model\BookRepo;

class Create extends WebController
{
    public function __invoke($action)
    {
        $branch = $this->session->keep('branch') ?? new Branch;
        $data = call([$this, $action], ['branch' => $branch]);

        $data['title'] = __('Create the book');
        $data['branch'] = $branch;

        return view('web/create/form_wrapper', $data);
    }

    public function genres(ModelGenre $modelGenre, $branch)
    {
        $totalGenres = new Collection($modelGenre->getTitles());
        $data['form'] = new BranchGenresForm($totalGenres, $branch);
        $data['sbmt'] = [
            'prev' => null,
            'next' => 'rules',
        ];
        $data['controls'] = 'form_controls';
        $data['step'] = 1;

        return $data;
    }

    public function rules($branch)
    {
        $data['form'] = new RulesCreateForm($branch);
        $data['sbmt'] = [
            'prev' => 'genres',
            'next' => 'authors',
        ];
        $data['controls'] = 'form_controls';
        $data['step'] = 2;

        return $data;
    }

    public function authors(AddAuthors $addAuthors, $branch)
    {
        $queryParams = $this->request->getQueryParams();
        $formData = $addAuthors->getFormData($queryParams, $this->user, $branch);

        $data['form'] = new AuthorsChoice($formData, $branch);
        $data['sbmt'] = [
            'prev' => 'rules',
            'next' => 'cover',
        ];
        $data['controls'] = 'form_controls';
        $data['step'] = 3;

        return $data;
    }

    public function cover($branch)
    {
        $this->app->add('coverpath', trim(Branch::COVERPATH, '.'));

        $data['form'] = new CoverForm($branch);
        $data['controls'] = 'cover_controls';
        $data['step'] = 4;

        return $data;
    }

    public function publish($branch)
    {
        $form = new PublishForm($branch);
        $ready = $form->ready($branch);
        $form->set('ready', $ready);
        $data['ready'] = $ready;
        $data['form'] = $form;
        $data['controls'] = 'publish_controls';
        $data['step'] = 5;

        return $data;
    }
}
