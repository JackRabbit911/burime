<?php declare(strict_types=1);

namespace App\Home\Controller;

use App\Home\Repository\StartRepo;
use Sys\Controller\WebController;
use Parsedown;

class StartGame extends WebController 
{
    private string $viewName;
    private string $title = 'Start the game';

    public function __construct(private Parsedown $parser) {}

    public function __invoke(StartRepo $repo)
    {
        $data['title'] = $this->title;

        if (!$this->user) {
            return view('home/startgame/no_user', $data);
        } elseif ($this->user->ownAuthors->empty()) {
            return view('home/startgame/no_authors', $data);
        }

        $data['count'] = $repo->getCountWorks();

        return view('home/startgame/start', $data);
    }

    protected function _before()
    {
        $this->i18n->addPath(APPPATH . 'app/Home/i18n');
    }
}
