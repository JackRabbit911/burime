<?php declare(strict_types=1);

namespace Auth\Controller;

use Sys\Controller\WebController;

class Demo extends WebController
{
    public function __invoke()
    {
        $this->tpl->getEngine()->getLoader()
            ->addPath(realpath(APPPATH . 'auth/views'), 'auth');

        $data['username'] = $this->user?->name ?? __('Guest');

        return view('@auth/demo', $data);
    }
}
