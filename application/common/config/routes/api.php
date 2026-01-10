<?php declare(strict_types=1);

use Adm\Controller\Users;
use App\Api\Author\Controller\AuthorSave;
use App\Api\Author\Controller\Group;
use App\Api\Branch\Controller\BranchSave;
use App\Api\Branch\Controller\MyBranch;
use App\Api\Common\Controller\Help;
use App\Api\Common\Controller\ReferenceBooks;
use App\Api\Common\Controller\Translate;
use App\Api\Private\Controller\MyController;
use App\Branch\Api\Controller\Branch;
// use App\Branch\Api\Controller\BranchSave;
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

    // 'branch.help'   => ['/api/branch/help/{step}', [Branch::class, 'gethelp']],

    // 'branch.save'   => ['/api/branch/save/{action?}', BranchSave::class],
    // 'branch.delete' => ['/api/branch/delete/{id}/{draft?}', [BranchSave::class, 'delete']],
    // 'branch.create' => ['/api/branch/create/{action}/{id?}/{draft?}', Branch::class],

    'api.help'      => ['/api/my/help/{path}', Help::class, ['path' => '.*']],
    'api.translate' => ['/api/my/gettranslate', Translate::class],
    'api.reference' => ['/api/my/reference/{action}', ReferenceBooks::class],

    'api.branchsave'=> ['/api/my/branch/action/{action}/{id?}', BranchSave::class],
    'api.my.branch' => ['/api/my/branch/{action}/{id?}/{draft?}', MyBranch::class],

    'api.my.group'  => ['/api/my/group/{action}/{id?}', Group::class],
    'api.authorsave'=> ['/api/my/author/{action}/{id?}', AuthorSave::class],
    'api.my'        => ['/api/my/{action}/{id?}', MyController::class],
];
