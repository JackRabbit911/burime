<?php

declare(strict_types=1);

namespace App\Home\Repository\About;

use Parsedown;

class CreateBranch
{
    private string $filePrefix = APPPATH . 'common/data/article/create_branch/';

    public function __construct(private Parsedown $parser){}

    public function getData(array $files)
    {
        $content = '';

        foreach ($files as $file) {
            $file = $this->filePrefix . $file . '.md';
            $content .= is_file($file) ? file_get_contents($file) : '';
        }

        return empty($content) ? 'no contents' : $this->parser->text($content);
    }
}
