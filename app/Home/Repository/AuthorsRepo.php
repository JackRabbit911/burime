<?php

declare(strict_types=1);

namespace App\Home\Repository;

use App\Home\Component\AuthorsForm;
use App\Home\Model\ModelAuthors;
use Common\Component\Pagination;
use Common\Component\Switcher;
use Psr\Http\Message\ServerRequestInterface;
use Sys\Contract\UserInterface;

class AuthorsRepo
{
    use AuthorsWorks;

    public function __construct(private ModelAuthors $model) {}

    public function get(ServerRequestInterface $request, ?int $user_id)
    {
        $query_params = $request->getQueryParams();

        $view = $this->getView($query_params, 'authors');

        $filter = $query_params['filter'] ?? '';
        [$limit, $offset, $search] = $this->getParams($query_params);
        [$authors, $selected] = $this->model->get($limit, $offset, $filter, $search, $user_id);

        $data = $this->getData($request, $selected, $limit);
        $data['title'] = 'Authors';
        $data['authors'] = $authors;
        $data['form'] = new AuthorsForm($query_params, $user_id);

        return [$view, $data];
    }
}
