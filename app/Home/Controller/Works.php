<?php declare(strict_types=1);

namespace App\Home\Controller;

use App\Home\Model\ModelWorks;
use Common\Component\Switcher;
use Sys\Controller\WebController;
use Sys\Paginator;

class Works extends WebController
{
    private $rowCount;
    private $paginationView = 'common/component/pagination';
    private $switcherView = 'common/component/switcher';
    private string $title = 'Works';
    private ModelWorks $model;

    public function __construct(ModelWorks $model)
    {
        $this->model = $model;
        $this->rowCount = $model->getCount();
    }

    public function __invoke()
    {
        $queryParams = $this->request->getQueryParams();

        if (isset($queryParams['show'])) {
            $func = $queryParams['show'];
            return $this->$func();
        }

        return $this->cards();
    }

    private function cards()
    {
        $data = $this->makeData(40);
        return view('home/books/cards', $data);
    }

    private function table()
    {
        $data = $this->makeData(40);
        return view('home/books/table', $data);
    }

    private function list()
    {
        $data = $this->makeData(40);
        return view('home/books/list', $data);
    }

    private function makeData($limit)
    {
        $paginator = new Paginator($this->request, $this->rowCount, $limit, $this->paginationView);
        $switcher = new Switcher($this->request, $this->switcherView);

        $offset = $paginator->offset($limit);

        return [
            'title' => $this->title,
            'books' => $this->model->get($limit, $offset),
            'paginator' => $paginator,
            'switcher' => $switcher,
        ];
    }
}
