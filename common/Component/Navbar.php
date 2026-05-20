<?php declare(strict_types = 1);

namespace Common\Component;

use Sys\Template\Component;
use Sys\Template\TemplateInterface;

class Navbar extends Component
{
    protected ?string $view = 'common/navbar';

    public function __construct()
    {
        $this->data = [
            'menu' => require APPPATH . 'common/data/navbar.php',
            'brand' => 'Burime',
        ];
    }
}
