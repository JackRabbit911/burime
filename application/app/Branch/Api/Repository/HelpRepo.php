<?php

declare(strict_types=1);

namespace App\Branch\Api\Repository;

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

    public function getHelp(int $key)
    {
        $file = $this->filePrefix . $this->stepHelps[$key] . '.md';
        $content = is_file($file) ? file_get_contents($file) : null;

        return $content ? $this->parser->text($content) : 'no content';
    }
}
