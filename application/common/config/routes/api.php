<?php declare(strict_types=1);

use App\Burime\Controller\DeletePostApi;
use App\Rating\RatingApi;
use Sys\Console\Controller as ConsoleController;

return [
    'console'   => ['/api/console/{model}/{method}', ConsoleController::class, ['model' => '[\w\/]+']],
    'rating'    => ['/api/rating/{action}/{post_id}', RatingApi::class],
    'post.confirm'=>['/api/post/{action}/{post_id}', DeletePostApi::class],
];
