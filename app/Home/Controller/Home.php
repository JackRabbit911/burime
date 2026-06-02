<?php

namespace App\Home\Controller;

use App\Home\Model\HomeRepo;
use Sys\Controller\WebController;

class Home extends WebController 
{
    private string $title = 'Literary game Burime';

    public function __construct(private HomeRepo $repo) {}

    public function __invoke()
    {
        [$books, $total] = $this->repo->getBranches(4);
        $data['title'] = $this->title;
        $data['count_branches'] = $total;
        $data['count_authors'] = $this->repo->getAuthorsCount();
        $data['authors'] = $this->repo->getAuthors(4);
        $data['books'] = $books;
        $data['post'] = $this->repo->getBestPost();

        return view('home/home', $data);
    }
}
