<?php

declare(strict_types=1);

namespace App\Author\Controller;

use Sys\Controller\WebController;

class NoAuthor extends WebController
{
    public function __invoke()
    {
        $tplPath = APPPATH . 'auth/views';
        $this->tpl->addPath(realpath($tplPath), 'auth');

        $data = [
            'role' => 'warning',
            'subject' => "Dear, {$this->user->name}, You need create own author",
            'msg' => 'Messages are addressed only to authors and signed only by authors.',
            'link' => [
                'href' => path('private', ['action' => 'authors']),
                'title' => 'Create my author',
            ],
        ];

        return view('@auth/common/alert', ['data' => $data]);
    }
}
