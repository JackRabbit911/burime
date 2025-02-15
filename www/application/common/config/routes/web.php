<?php declare(strict_types = 1);

use HttpSoft\Response\HtmlResponse;

return [
    'home' => ['/', fn() => new HtmlResponse('It works!!!')],
];
