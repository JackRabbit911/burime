<?php

declare(strict_types=1);

namespace App\Home\Controller;

use App\Api\Branch\Repository\DraftRepo;
use App\Home\Component\BooksForm;
use App\Home\Model\ModelWorks;
use Common\Component\Pagination;
use Common\Component\Switcher;
use Sys\Controller\WebController;
use Sys\Pagination\Pagination57;

class Works extends WebController
{
    private $paginationView = 'common/component/paginator';
    private $switcherView = 'common/component/switcher';
    private string $title = 'Works';

    public function __construct(private ModelWorks $model, private DraftRepo $repo) {}

    public function __invoke()
    {
        $query_params = $this->request->getQueryParams();
        $suffix = $query_params['show'] ?? 'cards';
        $view = "home/books/$suffix";
        $data = $this->makeData();

        return view($view, $data);
    }

    private function makeData(?int $limit = null)
    {
        $query_params = $this->request->getQueryParams();
        $page = $query_params['page'] ?? 1;

        $limit = $query_params['limit'] ?? $limit ?? 24;
        $offset = Pagination::offset((int) $page, (int) $limit);
        $genres = $query_params['genre'] ?? [];
        $search = $query_params['search'] ?? '';

        [$books, $selected] = $this->model->get((int) $limit, $offset, $genres, $search);

        $form = new BooksForm($this->request, $this->repo);
        $pagination = new Pagination($this->request, $selected, (int) $limit);
        $switcher = new Switcher($this->request, $this->switcherView);

        return [
            'title' => $this->title,
            'books' => $books,
            'switcher' => $switcher,
            'pagination' => $pagination,
            'form' => $form,
            'selected' => $selected,
            'total' => $this->model->getCount(),
        ];
    }
}
