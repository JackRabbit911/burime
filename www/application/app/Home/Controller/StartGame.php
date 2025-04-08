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

    private function prologue()
    {
        if (!$this->user) {
            $href = path('auth', ['action' => 'form']);
            $link = '<a class="link" href="' . $href . '">' . __('log in') . '</a>';

            return "<p>First of all, you need to $link to our website</p>";
        } else {
            return '';
        }
    }
}
