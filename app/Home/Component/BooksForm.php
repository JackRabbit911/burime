<?php

declare(strict_types=1);

namespace App\Home\Component;

use App\Api\Branch\Repository\DraftRepo;
use Sys\Template\Component;
use Psr\Http\Message\ServerRequestInterface;

class BooksForm extends Component
{
    protected ?string $view = 'home/books/component/form';

    public function __construct(array $query_params, private DraftRepo $repo)
    {
        $this->data['checked'] = $query_params['filter'] ?? [];
        $this->data['search'] = $query_params['search'] ?? '';
        $this->data['show'] = $query_params['show'] ?? 'cards';
        $this->data['genres'] = $repo->getTotalGenres();
        $this->data['reset'] = path('works') . '?show=' . $this->data['show'];
        $this->data['placeholder'] = 'Search by title and author';
    }
}
