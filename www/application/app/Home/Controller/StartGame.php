<?php declare(strict_types=1);

namespace App\Home\Controller;

use Sys\Controller\WebController;
use Parsedown;

class StartGame extends WebController 
{
    private string $title = 'How to play the game';

    public function __construct(private Parsedown $parser) {}

    public function start()
    {
        $data['title'] = $this->title;
        $data['article'] = $this->prologue();

        $file = APPPATH . 'common/data/article/startgame.md';
        $content = file_get_contents($file);
        
        $data['article'] .= $this->parser->text($content);

        return view('home/about', $data);
    }

    public function how_create_author()
    {
        return 'qq';
    }

    protected function _before()
    {
        $this->i18n->addPath(APPPATH . 'app/Home/i18n');
    }

    private function prologue()
    {
        if (!$this->user) {
            return view('home/startgame/no_user');
        } elseif ($this->user->ownAuthors->empty()) {
            return view('home/startgame/no_authors');
        } else {
            return '';
        }
    }
}
