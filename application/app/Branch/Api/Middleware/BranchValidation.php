<?php

declare(strict_types=1);

namespace App\Branch\Api\Middleware;

use Az\Validation\Middleware\ApiValidationMiddleware;

class BranchValidation extends ApiValidationMiddleware
{
    protected function setRules($request)
    {
        $this->validation
            ->rule('branch.id', 'integer')
            ->rule('branch.parent_id', 'integer')
            ->rule('branch.owner', 'integer')
            ->rule('branch.role', 'required|integer|inRange(0, 3)')
            ->rule('branch.title', 'required|text_utf8|maxWordsCount(8)')
            ->rule('branch.age_limit', 'required|integer|inRange(0, 21)')
            ->rule('branch.infomoderation', 'boolean')
            ->rule('branch.info.comments', 'boolean')
            ->rule('branch.info.signature', 'boolean')
            ->rule('branch.info.post_size', 'required|integer|inRange(50, 2000)')
            ->rule('branch.info.time_limit', 'required|integer|inRange(30, 720)')
            ->rule('branch.info.description', 'text_utf8|maxWordsCount(200)')
            ->rule('branch.info.rules', 'text_utf8|maxWordsCount(200)')
            ->rule('branch.info.bg_color', 'hex_color')
            ->rule('branch.info.text_color', 'hex_color')
            ->rule('branch.info.text_size', 'required|integer|inRange(5, 50)')
            ->rule('branch.info.bg_img', 'text_utf8')
            ->rule('branch.info.cover', 'text_utf8')
            ->rule('branch.genres', 'integer')
            ->rule('posts.first.id', 'integer')
            ->rule('posts.first.body', 'text_utf8')
            ->rule('posts.last.id', 'integer')
            ->rule('posts.last.body', 'text_utf8')
        ;
    }
}
