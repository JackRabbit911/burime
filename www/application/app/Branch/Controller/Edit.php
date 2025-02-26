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

    public function authors(BranchAuthors $branchAuthors, $branch)
    {
        $queryParams = $this->request->getQueryParams();

        $formData = $branchAuthors->getFormData($queryParams, $this->user, $branch);

        $data['form'] = new AuthorsChoice($formData, $branch);
        $data['tab'] = 'tab_authors';

        return $data;
    }

    public function cover($branch)
    {
        $this->app->add('coverpath', trim(Branch::COVERPATH, '.'));

        $data['form'] = new CoverForm($branch);
        $data['tab'] = 'tab_cover';

        return $data;
    }

    public function publish($branch)
    {
        $this->app->js('/assets/js/checkboxCheck.js');
        $data['form'] = new StatusForm($branch);
        $data['tab'] = 'tab_publish';

        return $data;
    }
}
