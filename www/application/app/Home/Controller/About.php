<?php declare(strict_types=1);

namespace App\Home\Controller;

use Parsedown;
use Sys\Controller\WebController;

class About extends WebController
{
    public function __construct(private Parsedown $parser) {}

    public function burime()
    {
        $data['title'] = 'About Burime';

        $file = APPPATH . 'common/data/article/burime.md';
        $content = file_get_contents($file);
        $data['article'] = $this->parser->text($content);
        
        return view('home/about', $data);
    }

    public function rules()
    {
        $data['title'] = 'About rules of burime';

        $data['article'] = 'Article about rules';
        
        return view('home/about', $data);
    }

    public function genres()
    {
        $data['title'] = 'About genres';

        $data['article'] = 'Article about genres';
        
        return view('home/about', $data);
    }
}
