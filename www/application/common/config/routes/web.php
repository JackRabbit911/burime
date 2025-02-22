<?php declare(strict_types = 1);

use App\Author\Controller\Author;
use App\Author\Controller\Controls;
use App\Author\Controller\Form;
use App\Burime\Controller\Burime;
use App\Home\Controller\About;
use App\Home\Controller\AuthorsList;
use App\Home\Controller\Home;
use App\Home\Controller\Works;
use App\Private\PrivateController;
use App\Message\MessageController;
use App\Burime\Controller\Participation;
use HttpSoft\Response\HtmlResponse;

return [
    'home'          => ['/', Home::class],
    'about'         => ['/about/{action}', About::class],
    'works'         => ['/works/{action?}', Works::class],
    'authors'       => ['/authors', AuthorsList::class],

    'author.controls'=>['/author/controls/{id?}', Controls::class],
    'author.form'   => ['/author/form/{id?}', Form::class],
    'author.save'   => ['/author/save/{id?}', [Form::class, 'save']],
    'author'        => ['/author/{action?}/{id}', Author::class],

    'branch'        => ['/branch/{branch_id}/{action?}/{post_id?}', Burime::class],

    'message'       => ['/message/{action}/{id?}/{author_id?}', MessageController::class],

    'private'       => ['/private/{action}', PrivateController::class],

    'participation' => ['/participation/{branch_id}/{action}/{author_id?}', Participation::class],
];
