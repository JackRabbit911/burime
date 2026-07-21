<?php

declare(strict_types=1);

namespace Adm\Controller;

use App\Api\Common\Controller\ApiContractController;

class Dicts extends ApiContractController
{
    public function sidebar()
    {
        $role = $this->user?->role ?? 0;
        $config = config('sidebar');

        return array_map(function ($item) use ($role) {
            $item->setDisabled($role);
            return $item;
        }, $config);
    }
}
