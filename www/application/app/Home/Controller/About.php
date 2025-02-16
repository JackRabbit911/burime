<?php declare(strict_types=1);

namespace App\Home\Controller;

use Sys\Controller\WebController;

class About extends WebController
{
    public function burime()
    {
        $data['title'] = 'About Burime';
        
        return view('home/about', $data);
    }

    public function rules()
    {
        $data['title'] = 'About rules of burime';
        
        return view('home/about', $data);
    }

    public function genres()
    {
        $data['title'] = 'About genres';
        
        return view('home/about', $data);
    }
}
