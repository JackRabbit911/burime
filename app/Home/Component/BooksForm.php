<?php

declare(strict_types=1);

namespace App\Home\Component;

use App\Api\Branch\Repository\DraftRepo;
use Psr\Http\Message\ServerRequestInterface;
use Sys\Template\Component;

class BooksForm extends Component
{
    protected ?string $view = 'home/books/component/filter/form';

    public function __construct(private ServerRequestInterface $request, private DraftRepo $repo)
    {
        $query_params = $request->getQueryParams();
        $this->data['checked'] = $query_params['genre'] ?? [];
        $this->data['search'] = $query_params['search'] ?? '';
        $this->data['show'] = $query_params['show'] ?? 'cards';
        $this->data['genres'] = $repo->getTotalGenres();
    }
}
