<?php

declare(strict_types=1);

namespace App\Branch\Api\Repository;

use Parsedown;

class HelpRepo
{
    private string $filePrefix = APPPATH . 'common/data/article/create_branch/';

    public function __construct(private Parsedown $parser){}

    public function genres()
    {
        $file = $this->filePrefix . 'genres.md';
        $content = is_file($file) ? file_get_contents($file) : null;

        return $content ? $this->parser->text($content) : 'no content';
    }
}
