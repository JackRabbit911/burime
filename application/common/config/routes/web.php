<?php declare(strict_types = 1);

use App\Api\UserGuest;
use App\Author\Controller\Author;
use App\Author\Controller\Controls;
use App\Author\Controller\Form;
use App\Author\Controller\NoAuthor;
use App\Branch\Controller\Branch;
// use App\Branch\Controller\CreateSave;
// use App\Branch\Controller\Edit;
// use App\Branch\Controller\EditSave;
use App\Burime\Controller\Burime;
use App\Burime\Controller\Participation;
use App\Burime\Controller\PostBranchSave;
use App\Burime\Controller\PostControls;
use App\Home\Controller\About;
use App\Home\Controller\AuthorsList;
use App\Home\Controller\Home;
use App\Home\Controller\Search;
use App\Home\Controller\StartGame;
use App\Home\Controller\Works;
use App\Message\Controller\Message;
use App\Private\PrivateController;
use App\Rating\Rating;
use App\Author\Controller\Api as AuthorApi;
use App\Burime\Controller\Api as BurimeApi;
use App\Chat\Controller\Chat;
use App\Home\Controller\AboutHowToCreate;
use Common\Controller\Avatar;
use Common\Controller\Front;

return [
    'avatar'        => ['/avatar/{action}/{id}/{lifetime?}', Avatar::class],

    'home'          => ['/', Home::class],
    'about.create'  => ['/about/how_to_create/{action}', AboutHowToCreate::class],
    'about'         => ['/about/{action}', About::class],
    'works'         => ['/works/{action?}', Works::class],
    'authors'       => ['/authors', AuthorsList::class],
    'search'        => ['/search', Search::class],
    'start'         => ['/how-to-play/{action}', StartGame::class],

    'author.controls'=>['/author/controls/{id?}', Controls::class],
    'author.form'   => ['/author/form/{id?}', Form::class],
    'author.save'   => ['/author/save/{id?}', [Form::class, 'save']],
    'author.guard'  => ['/author/need_create', NoAuthor::class],
    'author'        => ['/author/{action?}/{id}', Author::class],

    'branch.post'   => ['/branch/{branch_id}/save/{post_id?}', PostBranchSave::class],
    'branch'        => ['/branch/{branch_id}/{action?}/{post_id?}', Burime::class],

    // 'my.branch'     => ['/my/branch/{id?}', Front::class],
    'my'            => ['/my/{any?}', Front::class, ['any' => '.*']],

    // 'edit'          => ['/edit/{action}/{id}', Edit::class],
    // 'edit.save'     => ['/edit/post/{action}/{id}', EditSave::class],
    // 'create'        => ['/create/{action}/{id?}/{draft?}', Create::class],
    // 'create.save'   => ['/create/{action}', CreateSave::class],

    'message'       => ['/message/{action}/{id?}/{author_id?}', Message::class],
    'chat'          => ['/chat/{action?}/{room_id}/{author_id}', Chat::class],

    'private'       => ['/private/{action}', PrivateController::class],

    'participation' => ['/participation/{branch_id}/{action}/{author_id?}', Participation::class],
    'rating'        => ['/rating/{action}/{post_id}', Rating::class],
    'post'          => ['/post/{branch_id}/{action}/{post_id}', PostControls::class],
    // 'branch.post'   => ['/branch/{branch_id}/save/{post_id?}', PostBranchSave::class],

    'int.burime'    => ['/internal/burime/{action}/{id?}', BurimeApi::class],
    'int.author'    => ['/internal/author/{action}/{id?}', AuthorApi::class],

    'lab.react'     => ['/whoami', UserGuest::class],
];
