<?php declare(strict_types=1);

use Api\Controller\ApiTest;
use App\Burime\Controller\DeletePostApi;
use App\Rating\RatingApi;
use Sys\Console\Controller as ConsoleController;

return [
    'console'   => ['/api/console/{model}/{method}', ConsoleController::class, ['model' => '[\w\/]+']],
    'rating'    => ['/api/rating/{action}/{post_id}', RatingApi::class],
    'post.confirm'=>['/api/post/{action}/{post_id}', DeletePostApi::class],
    'api.test'  => ['/api/test', ApiTest::class, [], ['options', 'get', 'patch']],
];
