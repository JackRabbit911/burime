<?php

declare(strict_types=1);

namespace App\Branch\Api;

class FirstLastDTO
{
    public readonly string $first;
    public readonly string $last;

    public function __construct(array $params = [])
    {
        $this->first = $params['first'] ?? '';
        $this->last = $params['last'] ?? '';
    }
}
