<?php

declare(strict_types=1);

// use Adm\Controller\Auth as ControllerAuth;

use Adm\Controller\O2Auth;
use Adm\Controller\Users;

use Auth\Api\Controller\Auth;
use Auth\Api\Controller\Recovery;
use Auth\Api\Controller\Register;

use App\Api\Author\Controller\AuthorSave;
use App\Api\Author\Controller\Group;
use App\Api\Branch\Controller\BranchSave;
use App\Api\Branch\Controller\MyBranch;
use App\Api\Branch\Controller\BranchAuthorStatus;
use App\Api\Common\Controller\Authors;
use App\Api\Common\Controller\Help;
use App\Api\Common\Controller\ReferenceBooks;
use App\Api\Common\Controller\Translate;
use App\Api\Message\Controller\Additional;
use App\Api\Message\Controller\Message;
use App\Api\Private\Controller\MyController;
use App\Api\Private\Controller\Profile;
use App\Burime\Controller\DeletePostApi;
use App\Rating\RatingApi;
use Common\Controller\Informer;
use Sys\Console\Controller as ConsoleController;

// use App\Branch\Api\Controller\Branch;
// use App\Branch\Api\Controller\BranchSave;
// use Auth\Api\Controller\O2Auth;

return [
    'informer'      => ['api/informer', Informer::class],
    'console'       => ['/api/console/{model}/{method}', ConsoleController::class, ['model' => '[\w\/]+']],
    
    'rating'        => ['/api/rating/{action}/{post_id}', RatingApi::class],
    'post.confirm'  => ['/api/post/{action}/{post_id}', DeletePostApi::class],

    'adm.auth'      => ['/api/adm/auth/{action}', O2Auth::class],
    'api.adm.users' => ['/api/adm/users/{id?}', Users::class],

    'api.translate' => ['/api/gettranslate', Translate::class],
    'api.help'      => ['/api/my/help/{path}', Help::class, ['path' => '.*']],
    'api.reference' => ['/api/my/reference/{action}', ReferenceBooks::class],

    'api.authors'   => ['/api/my/common/authors/{action}/{id?}', Authors::class],
    'api.ba.status' => ['/api/my/branch/status/{action}/{id?}', BranchAuthorStatus::class],
    'api.branchsave'=> ['/api/my/branch/action/{action}/{id?}', BranchSave::class],
    'api.my.branch' => ['/api/my/branch/{action}/{id?}/{draft?}', MyBranch::class],
    'api.my.group'  => ['/api/my/group/{action}/{id?}', Group::class],
    'api.authorsave'=> ['/api/my/author/{action}/{id?}', AuthorSave::class],
    'api.message'   => ['/api/my/message/{action}/{id?}/{recipient?}', Message::class],
    'api.profile'   => ['/api/my/profile/{action?}', Profile::class],
    'api.additional'=> ['/api/my/additional/{action}/{id}', Additional::class],
    'api.my'        => ['/api/my/{action}/{id?}', MyController::class],

    ['/api/auth/{action?}/{code?}', Auth::class],
    ['/api/register/{action}/{code?}', Register::class],
    ['/api/recovery/{action}/{id?}/{code?}', Recovery::class],
];
    
    // 'api.auth'      => ['/api/auth/{action}', O2Auth::class],
    // 'branch.help'   => ['/api/branch/help/{step}', [Branch::class, 'gethelp']],
    // 'branch.save'   => ['/api/branch/save/{action?}', BranchSave::class],
    // 'branch.delete' => ['/api/branch/delete/{id}/{draft?}', [BranchSave::class, 'delete']],
    // 'branch.create' => ['/api/branch/create/{action}/{id?}/{draft?}', Branch::class],
