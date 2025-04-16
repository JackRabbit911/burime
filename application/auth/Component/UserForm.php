<?php

namespace Auth\Component;

use Sys\Template\Component;
use Sys\Template\ComponentForm;

class UserForm extends Component
{
    use ComponentForm;

    private string $viewPrefix = '@auth/';
    private string $view;
    // private string $config;
    private array $data;

    public function __construct($data)
    {
        $this->view = $this->viewPrefix . $data['view'];
        unset($data['view']);
        $this->data = $data;
    }

    public function render()
    {
        return $this->_render($this->data);
    }
}
