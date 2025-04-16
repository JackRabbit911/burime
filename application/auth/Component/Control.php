<?php

namespace Auth\Component;

use Sys\Template\Component;
use Sys\Template\TemplateInterface;

class Control extends Component
{
    private string $tplPath = APPPATH . 'auth/views';
    private $user;

    public function __construct(TemplateInterface $tpl, $user)
    {
        $tpl->getEngine()->getLoader()
            ->addPath(realpath($this->tplPath), 'auth');

        $this->user = $user;
    }

    public function render()
    {
        if ($this->user) {
            $data['href'] = path('auth', ['action' => 'logout']);
            $data['title'] = 'Log Out';
        } else {
            $data['href'] = path('auth', ['action' => 'form']);
            $data['title'] = 'Sign In';
        }
        return view('@auth/control', $data);
    }
}
