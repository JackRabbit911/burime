<?php declare(strict_types=1);

namespace App\Home\Controller;

use Parsedown;
use Sys\Controller\WebController;

class About extends WebController
{
    public function __construct(private Parsedown $parser) {}

    public function burime()
    {
        $data = $this->getData(__FUNCTION__, 'About burime');
        return view('home/about', $data);
    }

    public function glossary()
    {
        $data = $this->getData(__FUNCTION__);       
        return view('home/about', $data);
    }

    public function rules()
    {
        $data = $this->getData(__FUNCTION__, 'Rules of the game');
        return view('home/about', $data);
    }

    public function genres()
    {
        $data = $this->getData(__FUNCTION__, 'About genres');
        return view('home/about', $data);
    }

    private function getData($file, $title = null, $menu = null)
    {
        $menu = $menu ?: 'startgame'; //$file;
        $data['title'] = $title ?? ucfirst($file);
        $data['sidebar'] = 'home/startgame/sidebar.twig';
        $data['menu'] = require APPPATH . "common/data/article/sidebar/$menu.php";

        $file = APPPATH . "common/data/article/$file.md";

        if (is_file($file)) {
            $content = file_get_contents($file);
            $data['article'] = $this->parser->text($content);
        } else {
            $data['article'] = 'No contents';
        }

        return $data;
    }
}
