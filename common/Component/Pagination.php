<?php

declare(strict_types=1);

namespace Common\Component;

use Sys\Pagination\Pagination57;

class Pagination extends Pagination57
{
    protected ?string $view = 'common/component/pagination/pagination';
    protected array $perPages = [24, 60, 120,];
}
