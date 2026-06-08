<?php

namespace App\Home\Controller;

use App\Home\Model\HomeRepo;
use App\Home\Repository\BooksRepo;
use App\Home\Repository\AuthorsRepo;
use App\Home\Middleware\BooksFilterValidation;
use App\Home\Middleware\AuthorsFilterValidation;
use Sys\Controller\WebController;
use HttpSoft\Response\JsonResponse;

class Home extends WebController
{
    public function __invoke(HomeRepo $repo)
    {
        [$books, $count_branches] = $repo->getBranches(4);
        [$authors, $count_authors] = $repo->getAuthors(4);
        $data['title'] = 'Literary game Burime';
        $data['count_branches'] = $count_branches;
        $data['count_authors'] = $count_authors;
        $data['authors'] = $authors;
        $data['books'] = $books;
        $data['post'] = $repo->getBestPost();

        return view('home/home', $data);
    }

    #[BooksFilterValidation]
    public function works(BooksRepo $repo): string | JsonResponse
    {
        [$view, $data] = $repo->get($this->request);
        return is_ajax($this->request) ? $this->ajax($data) : view($view, $data);
    }

    #[AuthorsFilterValidation]
    public function authors(AuthorsRepo $repo): string | JsonResponse
    {
        [$view, $data] = $repo->get($this->request, $this->user?->id);
        return is_ajax($this->request) ? $this->ajax($data) : view($view, $data);
    }

    private function ajax(array $data): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'result' => [
                'counter' => $data['counter'],
                'content' => $data['content'],
            ],
        ]);
    }
}
