<?php

declare(strict_types=1);

namespace App\Api\Common\Controller;

use App\Api\Common\Controller\ApiContractController;
use Az\Route\Route;
use HttpSoft\Response\JsonResponse;

#[Route(methods: 'post')]
class Translate extends ApiContractController
{
    public function __invoke()
    {
        $json = $this->request->getBody()->getContents();
        $data = json_decode($json);

        return new JsonResponse([
            'success' => true,
            'result' => $this->i18n->getMap($data->filter),
        ]);
    }
}
