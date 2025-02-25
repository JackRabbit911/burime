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
    public function __invoke($func, $id)
    {
        $branch = $this->request->getAttribute('branch');

        $data = call([$this, $func], ['branch' => $branch]);
        $data['title'] = __('Edit the book: ') . $branch->title ?? '';

        if (!isset($data['branch'])) {
            $data['branch'] = $branch;
        }

        return view('branch/edit/tab_genres', $data);
    }

    public function genres(ModelGenre $modelGenre, $branch)
    {
        $genres = $modelGenre->getByBranch($branch->id);
        $genres = new Collection($genres);
        $branch->genres = $genres->props();

        $data['branch'] = $branch;

        $totalGenres = $modelGenre->getTitles();
        $data['form'] = new BranchGenresForm($totalGenres, $branch);
        $data['tab'] = 'tab_genres';

        return $data;
    }

    public function rules($branch)
    {
        $data['form'] = new RulesForm($branch);
        $data['tab'] = 'tab_rules';

        return $data;
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
