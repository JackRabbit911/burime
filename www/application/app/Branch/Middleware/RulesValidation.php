<?php declare(strict_types=1);

namespace App\Branch\Middleware;

use Attribute;
use Az\Validation\Middleware\ValidationMiddleware;
use Psr\Http\Message\ServerRequestInterface;

#[Attribute]
final class RulesValidation extends ValidationMiddleware
{
    protected function setRules(ServerRequestInterface $request)
    {
        $this->validation->rule('role', 'required|integer|inRange(0, 3)')
            ->rule('info[moderation]', 'boolean')
            ->rule('info[comments]', 'boolean')
            ->rule('info[signature]', 'boolean')
            ->rule('age_limit', 'required|integer|inRange(0, 21)')
            ->rule('info[post_size]', 'required|integer|inRange(50, 2000)')
            ->rule('info[time_limit]', 'required|integer|inRange(30, 720)')
            ->rule('title', 'required|text_utf8|maxWordsCount(8)')
            ->rule('info[description]', 'text_utf8|maxWordsCount(200)')
            ->rule('info[rules]', 'text_utf8|maxWordsCount(200)');
    }
}
