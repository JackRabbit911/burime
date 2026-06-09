<?php

declare(strict_types=1);

namespace App\Home\Component;

use App\Api\Branch\Repository\DraftRepo;
use Common\Enum\MemberRole;
use Sys\Template\Form;
use Psr\Http\Message\ServerRequestInterface;

class AuthorsForm extends Form
{
    protected ?string $view = 'home/authors/component/form';

    public function __construct(array $query_params, ?int $user_id)
    {
        $this->data['filter'] = $query_params['filter'] ?? '';
        $this->data['search'] = [
            'name' => 'search',
            'value' => $query_params['search'] ?? '',
            'placeholder' => 'Search by alias',
        ];
        $this->data['show'] = $query_params['show'] ?? 'cards';
        $this->data['options'] = $user_id ? MemberRole::getFilters() : ['groups'];
        $this->data['options'][] = 'authors';
        $this->data['reset'] = path('home', ['action' => 'authors']);

        $this->js('assets/js/search.js');
    }
}
