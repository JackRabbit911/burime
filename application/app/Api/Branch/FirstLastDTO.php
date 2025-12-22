<?php

declare(strict_types=1);

namespace App\Api\Branch;

use stdClass;

class FirstLastDTO
{
    public readonly ?object $first;
    public readonly ?object $last;

    public function __construct(array $params = [])
    {
        $default = new stdClass;
        $default->id = null;
        $default->body = '';
        $default->author_id = null;

        $this->first = $params['first'] ?? $default;
        $this->last = $params['last'] ?? $default;
    }
}
