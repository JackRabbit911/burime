<?php

declare(strict_types=1);

namespace App\Home\Repository;

use Common\Component\Pagination;
use Common\Component\Switcher;
use Psr\Http\Message\ServerRequestInterface;

trait AuthorsWorks
{
    private function getView(array $query_params, string $part): array
    {
        $suffix = $query_params['show'] ?? 'cards';
        return ["home/$part/$suffix", "home/$part/content/$suffix"];
    }

    private function getParams(array $query_params): array
    {
        $page = $query_params['page'] ?? 1;
        $limit = $query_params['limit'] ?? $limit ?? 24;
        $offset = Pagination::offset((int) $page, (int) $limit);
        $search = $query_params['search'] ?? '';

        return [(int) $limit, $offset, $search];
    }

    private function getData(ServerRequestInterface $request, int $selected, int $limit): array
    {
        return [
            'counter' => view('home/common/counter', [
                'selected' => $selected,
                'total' => $this->model->getCount()
            ]),
            'switcher' => new Switcher($request),
            'pagination' => $pagination = new Pagination($request, $selected, (int) $limit),
        ];
    }
}
