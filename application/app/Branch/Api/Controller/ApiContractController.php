<?php

declare(strict_types=1);

namespace App\Branch\Api\Controller;

use Sys\Controller\ApiController;

abstract class ApiContractController extends ApiController
{
    protected function _after(&$response)
    {
        $response = [
            'success' => true,
            'result' => $response,
        ];
    }
}
