<?php declare(strict_types = 1);

use App\Home\Controller\About;
use App\Home\Controller\Home;
use HttpSoft\Response\HtmlResponse;

return [
    'home'      => ['/', Home::class],
    'about'     => ['/about/{action}', About::class],
];
