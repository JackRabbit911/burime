<?php

declare(strict_types=1);

namespace App\Api;

use HttpSoft\Response\TextResponse;
use Sys\Controller\WebController;

class UserGuest extends WebController
{
    public function __construct(){}

    public function __invoke()
    {
        $name = ($this->user) ? $this->user->name : 'Guest';

        return new TextResponse($name);
    }
}
