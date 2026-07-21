<?php

declare(strict_types=1);

namespace Adm\Controller;

use App\Api\Common\Controller\ApiContractController;
use HttpSoft\Response\EmptyResponse;

class Home extends ApiContractController
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

    public function dashboard()
    {
        $role = $this->user?->role ?? 0;

        return 'Здесь будет какой-нибудь дашборд';
    }

    public function pages()
    {
        return 'Здесь будет панель управления страницами';
    }
}
