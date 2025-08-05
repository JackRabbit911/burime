<?php declare(strict_types=1);

namespace Auth\Api\Job;

use Auth\Api\Model\ModelRefreshToken;

class TokensGC
{
    public function __construct(private ModelRefreshToken $model){}

    public function __invoke()
    {
        $lifetime = config('api_o2auth', 'refresh_lifetime');
        return $this->model->gc($lifetime);
    }
}
