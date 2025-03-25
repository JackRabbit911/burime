<?php declare(strict_types=1);

namespace App\Home\Controller;

use Sys\Controller\WebController;

class Search extends WebController
{
    public function __invoke()
    {
        $data['title'] = 'Search';
        return view('home/search', $data);
    }
}
