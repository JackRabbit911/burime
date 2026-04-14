<?php declare(strict_types=1);

namespace App\Home\Controller;

use App\Home\Repository\About\CreateBranch;
use Sys\Controller\WebController;
use Sys\Helper\Facade\Text;

class AboutHowToCreate extends WebController
{
    public function __construct(private CreateBranch $repo) {}

    public function new_branch()
    {
        $files = [
            'genres',
            'rules',
        ];

        $data = $this->getData($files, 'How to create a new project');
        return view('home/about', $data);
    }

    public function child_branch()
    {
        $data = $this->getData([], 'How to create a child branch');
        return view('home/about', $data);
    }

    private function getData(array $files, string $title, $menu = null)
    {
        $menu = $menu ?: 'startgame';
        $data['title'] = $title;
        $data['sidebar'] = 'home/startgame/sidebar.twig';
        $data['menu'] = require APPPATH . "common/data/article/sidebar/$menu.php";
        $data['article'] = $this->repo->getData($files);

        return $data;
    }
}
