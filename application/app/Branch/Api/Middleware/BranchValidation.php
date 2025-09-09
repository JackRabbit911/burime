<?php

declare(strict_types=1);

namespace App\Branch\Api\Middleware;

use Az\Validation\Middleware\ApiValidationMiddleware;

class BranchValidation extends ApiValidationMiddleware
{
    protected function setRules($request)
    {
        $this->validation
            ->rule('id', 'integer')
            ->rule('parent_id', 'integer')
            ->rule('owner', 'integer')
            ->rule('role', 'required|integer|inRange(0, 3)')
            ->rule('title', 'required|text_utf8|maxWordsCount(8)')
            ->rule('age_limit', 'required|integer|inRange(0, 21)')
            ->rule('info[moderation]', 'boolean')
            ->rule('info[comments]', 'boolean')
            ->rule('info[signature]', 'boolean')
            ->rule('info[post_size]', 'required|integer|inRange(50, 2000)')
            ->rule('info[time_limit]', 'required|integer|inRange(30, 720)')
            ->rule('info[description]', 'text_utf8|maxWordsCount(200)')
            ->rule('info[rules]', 'text_utf8|maxWordsCount(200)')
            ->rule('info[bg_color]', 'hex_color')
            ->rule('info[text_color]', 'hex_color')
            ->rule('info[text_size]', 'required|integer|inRange(5, 50)')
            ->rule('info[bg_img]', 'text_utf8')
            ->rule('info[cover]', 'text_utf8')
            ->rule('genres', 'integer')
        ;
    }
}
