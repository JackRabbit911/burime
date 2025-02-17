<?php declare(strict_types=1);

namespace App\Author\Controller;

use App\Author\Model\ModelAuthor;
use Common\Component\Switcher;
use Sys\Paginator;
use Sys\Controller\WebController;

final class AuthorsList extends WebController
{
    private ModelAuthor $model;
    private int $countRows;
    private string $paginationView = 'web/common/pagination';
    private string $switcherView = 'web/common/switcher';
    private bool $groupsOnly = false;

    public function __construct(ModelAuthor $model)
    {
        $this->model = $model;
        $this->countRows = $model->getCount();
    }

    public function __invoke()
    {
        $queryParams = $this->request->getQueryParams();

        if (isset($queryParams['filter'])) {
            $this->groupsOnly = true;
        }

        if (isset($queryParams['show'])) {
            $func = $queryParams['show'];
            return $this->$func();
        }

        return $this->cards();
    }

    private function cards()
    {
        $data = $this->makeData(40);
        return view('web/author/cards', $data);
    }

    private function table()
    {
        $data = $this->makeData(40);
        return view('web/author/table', $data);
    }

    private function list()
    {
        $data = $this->makeData(80);
        return view('web/author/list', $data);
    }

    private function makeData($limit)
    {
        $this->app->add('groupSwitcher', true);
        $paginator = new Paginator($this->request, $this->countRows, $limit, $this->paginationView);
        $switcher = new Switcher($this->request, $this->switcherView);

        $offset = $paginator->offset($limit);
        $authors = $this->model->get($limit, $offset, $this->groupsOnly);

        return [
            'title' => 'Authors',
            'authors' => $authors,
            'paginator' => $paginator,
            'switcher' => $switcher,
        ];
    }
}
