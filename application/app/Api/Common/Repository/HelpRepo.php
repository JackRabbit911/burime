<?php

declare(strict_types=1);

namespace App\Api\Common\Repository;

use Parsedown;

class HelpRepo
{
    private string $filePrefix = APPPATH . 'common/data/article/create_branch/';
    private array $stepHelps = [
        1 => 'genres',
        2 => 'rules',
        3 => 'authors',
        4 => 'cover',
    ];

    public function __construct(private Parsedown $parser){}

    public function __invoke(int $key)
    {
        $filename = $this->stepHelps[$key] ?? false;

        if (!$filename) {
            return false;
        }

        $file = $this->filePrefix . $filename . '.md';
        $content = is_file($file) ? file_get_contents($file) : null;

        return $content ? $this->parser->text($content) : 'no content';
    }
}
