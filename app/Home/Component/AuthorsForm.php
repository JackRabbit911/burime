<?php

declare(strict_types=1);

namespace App\Home\Component;

use App\Api\Branch\Repository\DraftRepo;
use Common\Enum\MemberRole;
use Sys\Template\Component;
use Psr\Http\Message\ServerRequestInterface;

class AuthorsForm extends Component
{
    protected ?string $view = 'home/authors/component/form';

    public function __construct(array $query_params, ?int $user_id)
    {
        $this->data['filter'] = $query_params['filter'] ?? '';
        $this->data['search'] = $query_params['search'] ?? '';
        $this->data['show'] = $query_params['show'] ?? 'cards';
        $this->data['placeholder'] = 'Search by alias';
        $this->data['options'] = $user_id ? MemberRole::getFilters() : ['groups'];
        $this->data['options'][] = 'authors';
        $this->data['reset'] = path('authors', []);
    }
}
