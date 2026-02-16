<?php

declare(strict_types=1);

namespace App\Api\Auth\Controller;

use Az\Route\Route;

class Auth extends ApiAuthController
{
    public function __construct(){}

    #[Route(methods: 'post')]
    public function __invoke()
    {
        $data = $this->request->getBody()->getContents();
        $data = json_decode($data);

        return $data;
    }
}
