<?php declare(strict_types=1);

// use Api\Controller\ApiTest;

use Adm\Controller\Users;
use App\Burime\Controller\DeletePostApi;
use App\Rating\RatingApi;
use Auth\Api\Controller\O2Auth;
use Sys\Console\Controller as ConsoleController;

return [
    'console'       => ['/api/console/{model}/{method}', ConsoleController::class, ['model' => '[\w\/]+']],
    'rating'        => ['/api/rating/{action}/{post_id}', RatingApi::class],
    'post.confirm'  => ['/api/post/{action}/{post_id}', DeletePostApi::class],
    'api.auth'      => ['/api/auth/{action}', O2Auth::class],
    'api.adm.users' => ['/api/adm/users', Users::class],
];
