<?php

declare(strict_types=1);

namespace App\Home\Component;

use App\Api\Branch\Repository\DraftRepo;
use Sys\Template\Component;
use Sys\Template\Form;
use Psr\Http\Message\ServerRequestInterface;

class BooksForm extends Form
{
    protected ?string $view = 'home/books/component/form';

    public function __construct(array $query_params, private DraftRepo $repo)
    {
        $this->data['checked'] = $query_params['filter'] ?? [];
        $this->data['search'] = [
            'name' => 'search',
            'value' => $query_params['search'] ?? null,
            'placeholder' => 'Search by title and author'
        ];
        $this->data['show'] = $query_params['show'] ?? 'cards';
        $this->data['genres'] = $repo->getTotalGenres();
        $this->data['reset'] = path('home', ['action' => 'works']) . '?show=' . $this->data['show'];

        $this->js('assets/js/testform.js');
    }
}
