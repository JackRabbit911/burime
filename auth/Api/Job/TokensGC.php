<?php

declare(strict_types=1);

namespace Auth\Api\Job;

use Auth\Api\Model\ModelRefreshToken;

class TokensGC
{
    public function __construct(
        private ModelRefreshToken $model
    ) {}

    public function __invoke()
    {
        return $this->model->gc();
    }
}
