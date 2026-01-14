<?php

declare(strict_types=1);

namespace App\Api\Common\Controller;

use App\Api\Common\Model\ModelAuthors;

class Authors extends ApiContractController
{
    public function group(ModelAuthors $model, ?int $id)
    {
        return $model->getByGroup($id);
    }
}
