<?php

declare(strict_types=1);

namespace App\Api\Branch;

use stdClass;

class BranchDTO
{
    public readonly ?int $id;
    public readonly ?int $parent_id;
    public readonly ?int $owner;
    public readonly ?string $title;
    public readonly int $role;
    public readonly int $age_limit;
    public readonly stdClass $cover;
    public readonly stdClass $info;
    public readonly array $genres;
    public readonly array $members;

    public function __construct(array $params = [])
    {
        $this->id = $params['id'] ?? null;
        $this->parent_id = $params['parent_id'] ?? null;
        $this->owner = $params['owner'] ?? null;
        $this->title = $params['title'] ?? '';
        $this->role = $params['role'] ?? 0;
        $this->age_limit = $params['age_limit'] ?? 0;
        
        $rules = new stdClass();

        if (isset($params['info']) && is_array($params['info'])) {
            $params['info'] = (object) $params['info'];
        }
        
        $rules->moderation = $params['info']->moderation ?? 0;
        $rules->allow_comments = $params['info']->allow_comments ?? 1;
        $rules->signature = $params['info']->signature ?? 1;
        $rules->post_size = $params['info']->post_size ?? 200;
        $rules->time_limit = $params['info']->time_limit ?? 120;
        $rules->description = $params['info']->description ?? '';
        $rules->rules = $params['info']->rules ?? '';
        
        $this->info = $rules;

        $cover = new stdClass();

        if (isset($params['cover']) && is_array($params['cover'])) {
            $params['cover'] = (object) $params['cover'];
        }
        
        $cover->bg_color = $params['cover']->bg_color ?? '#eeeeee';
        $cover->text_color = $params['cover']->text_color ?? '#333333';
        $cover->text_size = $params['cover']->text_size ?? 12;
        $cover->cover = $params['cover']->cover ?? '';
        $cover->bg_img = $params['cover']->bg_img ?? '';

        $this->cover = $cover;
    }
}
