<?php

declare(strict_types=1);

namespace Adm\Controller;

// use Adm\Middleware\AuthValidation;
use App\Api\Common\Controller\ApiContractController;
// use Auth\Api\Repository\AuthRepo;
// use Az\Route\Route;
// use HttpSoft\Response\EmptyResponse;

class Test extends ApiContractController
{
    public function __invoke()
    {
        return ['user' => $this->user ?? 'Guest'];
    }

    public function act1()
    {
        return ['user' => $this->user ?? 'Guest'];
    }

    public function act2()
    {
        return ['user' => $this->user ?? 'Guest'];
    }
}
