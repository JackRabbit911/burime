<?php

declare(strict_types=1);

namespace App\Branch\Api;

use stdClass;

class BranchDTO
{
    public readonly ?int $id;
    public readonly ?int $parent_id;
    public readonly ?int $owner;
    public readonly ?string $title;
    public readonly int $role;
    public readonly int $age_limit;
    public readonly ?string $cover;
    public readonly stdClass $info;
    public readonly array $genres;
    public readonly array $authors;

    public function __construct(array $params = [])
    {
        $this->id = $params['id'] ?? null;
        $this->parent_id = $params['parent_id'] ?? null;
        $this->owner = $params['owner'] ?? null;
        $this->title = $params['title'] ?? null;
        $this->role = $params['role'] ?? 0;
        $this->age_limit = $params['age_limit'] ?? 0;
        $this->cover = $params['cover'] ?? null;
        $this->genres = $params['genres'] ?? [];
        $this->authors = $params['authors'] ?? [];
        
        $rules = new stdClass();
        $rules->moderation = $params['info']->moderation ?? 0;
        $rules->allow_comments = $params['info']->allow_comments ?? 1;
        $rules->signature = $params['info']->signature ?? 0;
        $rules->post_size = $params['info']->post_size ?? 200;
        $rules->time_limit = $params['info']->time_limit ?? 120;
        $rules->description = $params['info']->description ?? '';
        $rules->rules = $params['info']->rules ?? '';
        $rules->bg_color = $params['info']->bg_color ?? '#eeeeee';
        $rules->text_color = $params['info']->text_color ?? '#333333';

        $this->info = $rules;
    }
}
