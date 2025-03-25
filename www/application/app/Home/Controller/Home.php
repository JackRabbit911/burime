<?php

namespace App\Home\Controller;

// use Common\Repository\HomeRepo;
use Sys\Controller\WebController;

class Home extends WebController 
{
    // private HomeRepo $repo;
    private string $title = 'Literary game Burime';

    // public function __construct(HomeRepo $repo)
    // {
    //     $this->repo = $repo;
    // }

    public function __invoke()
    {
        // dd(path('auth', ['action' => 'form']));
        $data['title'] = $this->title;
        // $data['count_branches'] = $this->repo->getBranchesCount();
        // $data['count_authors'] = $this->repo->getAuthorsCount();
        // $data['best_authors'] = $this->repo->getAuthors(4);
        // $data['best_branches'] = $this->repo->getBranches(4);

        return view('home/home', $data);
    }
}
