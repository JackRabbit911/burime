<?php

declare(strict_types=1);

namespace App\Api\Common\Controller;

use App\Api\Common\Controller\ApiContractController;
use Parsedown;

class Help extends ApiContractController
{
    private string $filePrefix = APPPATH . 'common/data/article/';

    public function __invoke(Parsedown $parser, string $path)
    {
        $file = $this->filePrefix . $path . '.md';
        $content = is_file($file) ? file_get_contents($file) : null;

        return $content ? $parser->text($content) : 'no content';
    }
}
