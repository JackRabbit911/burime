<?php declare(strict_types = 1);

use App\Author\Controller\Author;
use App\Author\Controller\Controls;
use App\Author\Controller\Form;
use App\Branch\Controller\Create;
use App\Branch\Controller\CreateSave;
use App\Branch\Controller\Edit;
use App\Branch\Controller\EditSave;
use App\Burime\Controller\Burime;
use App\Burime\Controller\Participation;
use App\Burime\Controller\PostBranchSave;
use App\Burime\Controller\PostControls;
use App\Home\Controller\About;
use App\Home\Controller\AuthorsList;
use App\Home\Controller\Home;
use App\Home\Controller\Search;
use App\Home\Controller\Works;
use App\Message\Controller\Message;
use App\Private\PrivateController;
use App\Message\MessageController;
use App\Rating\Rating;
use HttpSoft\Response\HtmlResponse;

return [
    'home'          => ['/', Home::class],
    'about'         => ['/about/{action}', About::class],
    'works'         => ['/works/{action?}', Works::class],
    'authors'       => ['/authors', AuthorsList::class],
    'search'        => ['/search', Search::class],

    'author.controls'=>['/author/controls/{id?}', Controls::class],
    'author.form'   => ['/author/form/{id?}', Form::class],
    'author.save'   => ['/author/save/{id?}', [Form::class, 'save']],
    'author'        => ['/author/{action?}/{id}', Author::class],

    'branch.post'   => ['/branch/{branch_id}/save/{post_id?}', PostBranchSave::class],
    'branch'        => ['/branch/{branch_id}/{action?}/{post_id?}', Burime::class],
    'edit'          => ['/edit/{action}/{id}', Edit::class],
    'edit.save'     => ['/edit/post/{action}/{id}', EditSave::class],
    'create'        => ['/create/{action}', Create::class],
    'create.save'   => ['/create/{action}', CreateSave::class],

    'message'       => ['/message/{action}/{id?}/{author_id?}', Message::class],

    'private'       => ['/private/{action}', PrivateController::class],

    'participation' => ['/participation/{branch_id}/{action}/{author_id?}', Participation::class],
    'rating'        => ['/rating/{action}/{post_id}', Rating::class],
    'post'          => ['/post/{branch_id}/{action}/{post_id}', PostControls::class],
    // 'branch.post'   => ['/branch/{branch_id}/save/{post_id?}', PostBranchSave::class],
];
