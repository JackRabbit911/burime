<?php

declare(strict_types=1);

namespace App\Home\Repository;

use App\Home\Model\ModelWorks;
use App\Home\Component\BooksForm;
use App\Api\Branch\Repository\DraftRepo;
use Psr\Http\Message\ServerRequestInterface;

class BooksRepo
{
    use AuthorsWorks;

    public function __construct(private ModelWorks $model, private DraftRepo $repo) {}

    public function get(ServerRequestInterface $request)
    {
        
        $query_params = $request->getQueryParams();
        $validation_response = $request->getAttribute('validation');

        if ($validation_response) {
            $query_params = [];
        }

        [$view, $view_content] = $this->getView($query_params, 'books');

        $filter = $query_params['filter'] ?? [];
        [$limit, $offset, $search] = $this->getParams($query_params);
        [$books, $selected] = $this->model->get($limit, $offset, $filter, $search);

        $data = $this->getData($request, $selected, $limit);
        $data['title'] = 'Works';
        $data['books'] = $books;
        $data['content'] = view($view_content, $data);
        $data['form'] = (new BooksForm($query_params, $this->repo));

        return [$view, $data];
    }
}
