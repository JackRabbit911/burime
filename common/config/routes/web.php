<?php declare(strict_types = 1);

use App\Author\Controller\Author;
use App\Author\Controller\Controls;
use App\Author\Controller\Api as AuthorApi;
use App\Burime\Controller\Burime;
use App\Burime\Controller\PostBranchSave;
use App\Burime\Controller\PostControls;
use App\Burime\Controller\Participation;
use App\Home\Controller\About;
use App\Home\Controller\Home;
use App\Home\Controller\StartGame;
use App\Home\Controller\AboutHowToCreate;
use App\Rating\Rating;
use Common\Controller\AuthFront;
use Common\Controller\Front;
use Common\Controller\Avatar;

// use App\Home\Controller\Works;
// use App\Home\Controller\AuthorsList;
// use App\Home\Controller\Search;
// use App\Api\UserGuest;
// use App\Author\Controller\Form;
// use App\Author\Controller\NoAuthor;
// use App\Message\Controller\Message;
// use App\Private\PrivateController;
// use App\Burime\Controller\Api as BurimeApi;
// use App\Chat\Controller\Chat;

use App\Api\Message\Controller\Message;

return [
    'my'            => ['/my/{any?}', Front::class, ['any' => '.*']],
    'auth'          => ['/auth/{any?}', AuthFront::class, ['any' => '.*']],
    'avatar'        => ['/ava/{action}/{id}/{lifetime?}', Avatar::class],

    'about.create'  => ['/about/how_to_create/{action}', AboutHowToCreate::class],
    'about'         => ['/about/{action}', About::class],
    'start'         => ['/start', StartGame::class],
    'author.controls'=>['/author/controls/{id?}', Controls::class],
    'author'        => ['/author/{action?}/{id}', Author::class],
    'branch.post'   => ['/branch/{branch_id}/save/{post_id?}', PostBranchSave::class],
    'branch'        => ['/branch/{branch_id}/{action?}/{post_id?}', Burime::class],
    'rating'        => ['/rating/{action}/{post_id}', Rating::class],
    'post'          => ['/post/{branch_id}/{action}/{post_id}', PostControls::class],
    
    'participation' => ['/participation/{branch_id}/{action}/{author_id?}', Participation::class],
    'int.author'    => ['/internal/author/{action}/{id?}', AuthorApi::class],
    
    'home'          => ['/{action?}', Home::class, ['action' => 'works|authors|']],

    // 'author.form'   => ['/author/form/{id?}', Form::class],
    // 'author.save'   => ['/author/save/{id?}', [Form::class, 'save']],
    // 'author.guard'  => ['/author/need_create', NoAuthor::class],
    // 'message'       => ['/message/{action}/{id?}/{author_id?}', Message::class],
    // 'chat'          => ['/chat/{action?}/{room_id}/{author_id}', Chat::class],
    // 'private'       => ['/private/{action}', PrivateController::class],
    // 'int.burime'    => ['/internal/burime/{action}/{id?}', BurimeApi::class],
    // 'lab.react'     => ['/whoami', UserGuest::class],

    'message'   => ['/my/message/{action}/{id?}/{recipient?}', Message::class],
];
