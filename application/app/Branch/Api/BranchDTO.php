<?php

declare(strict_types=1);

namespace App\Branch\Api;

class BranchDTO
{
    public readonly ?int $id;
    public readonly ?int $parent_id;
    public readonly int $owner;
    public readonly ?string $title;
    public readonly int $role;
    public readonly int $age_limit;
    public readonly ?string $cover;

    public function __construct(int $owner, ?array $params = null)
    {
        $this->id = $params['id'] ?? null;
        $this->parent_id = $params['parent_id'] ?? null;
        $this->owner = $owner;
        $this->title = $params['title'] ?? null;
        $this->role = $params['role'] ?? 0;
        $this->age_limit = $params['age_limit'] ?? 0;
        $this->cover = $params['cover'] ?? null;
    }
}
