<?php declare(strict_types = 1);

namespace Common\Component;

use Sys\Template\Component;
use Sys\Template\TemplateInterface;

class Navbar extends Component
{
    private TemplateInterface $tpl;
    private $menu;
    private $brand = 'Burime';

    public function __construct(TemplateInterface $tpl)
    {
        $this->tpl = $tpl;
        $this->menu = require APPPATH . 'common/data/navbar.php';
        // $prefix = 'data/navbar/';
        // $config = $prefix . $config;
        // $this->config = $config;
        // $this->brand = $brand;
    }

    public function render()
    {
        $data = [
            'menu' => $this->menu,
            'brand' => $this->brand,
        ];

        return $this->tpl->render('common/navbar', $data);
    }
}
