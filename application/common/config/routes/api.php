<?php declare(strict_types=1);

// use Api\Controller\ApiTest;

use Adm\Controller\Users;
use App\Branch\Api\Controller\Branch;
use App\Branch\Api\Controller\BranchHelp;
use App\Branch\Api\Controller\BranchSave;
use App\Burime\Controller\DeletePostApi;
use App\Rating\RatingApi;
use Auth\Api\Controller\O2Auth;
use Sys\Console\Controller as ConsoleController;

return [
    'console'       => ['/api/console/{model}/{method}', ConsoleController::class, ['model' => '[\w\/]+']],
    'rating'        => ['/api/rating/{action}/{post_id}', RatingApi::class],
    'post.confirm'  => ['/api/post/{action}/{post_id}', DeletePostApi::class],
    'api.auth'      => ['/api/auth/{action}', O2Auth::class],
    'api.adm.users' => ['/api/adm/users/{id?}', Users::class],
    'branch.create' => ['/api/branch/create/{action}/{id?}', Branch::class],
    'branch.help'   => ['/api/branch/help/{step}', BranchHelp::class],
    'branch.save'   => ['/api/branch/save', BranchSave::class],
];
