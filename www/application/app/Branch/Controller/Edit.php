<?php declare(strict_types=1);

namespace App\Branch\Controller;

use App\Branch\Component\AuthorsChoice;
use App\Branch\Component\BranchGenresForm;
use App\Branch\Component\CoverForm;
use App\Branch\Component\RulesForm;
use App\Branch\Component\StatusForm;
use App\Branch\Model\ModelGenre;
use App\Branch\Service\BranchAuthors;
use App\Branch\Middleware\OwnerBranchGuard;
use App\Branch\Branch;
use Sys\Controller\WebController;
use Sys\Collection\Collection;
use App\Author\Middleware\UserAuthorsMiddleware;

#[OwnerBranchGuard]
class Edit extends WebController
{
    private array $data;

    protected function _before()
    {
        $this->data['branch'] = $this->request->getAttribute('branch');
        $this->data['title'] = __('Edit the book: ') . $this->data['branch']->title ?? '';
    }

    public function genres(ModelGenre $modelGenre, $id)
    {
        $genres = $modelGenre->getByBranch($id);
        $genres = new Collection($genres);
        $this->data['branch']->genres = $genres->props();

        $totalGenres = $modelGenre->getTitles();
        $this->data['form'] = new BranchGenresForm($totalGenres, $this->data['branch']);

        return view('branch/edit/genres', $this->data);
    }

    public function rules($id)
    {
        $this->data['form'] = new RulesForm($this->data['branch']);
        return view('branch/edit/rules', $this->data);
    }

    #[UserAuthorsMiddleware]
    public function authors(BranchAuthors $branchAuthors, $id)
    {
        $queryParams = $this->request->getQueryParams();
        $formData = $branchAuthors->getFormData($queryParams, $this->user, $this->data['branch']);

        $this->data['form'] = new AuthorsChoice($formData, $this->data['branch']);
        return view('branch/edit/authors', $this->data);
    }

    public function cover($id)
    {
        $this->app->add('coverpath', trim(Branch::COVERPATH, '.'));

        $this->data['form'] = new CoverForm($this->data['branch']);
        return view('branch/edit/cover', $this->data);
    }

    public function publish($branch)
    {
        $this->app->js('/assets/js/checkboxCheck.js');
        $data['form'] = new StatusForm($branch);

        return view('branch/edit/publish', $this->data);
    }
}
