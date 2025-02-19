<?php declare(strict_types = 1);

use App\Author\Controller\Author;
use App\Author\Controller\Controls;
use App\Author\Controller\Form;
use App\Home\Controller\About;
use App\Home\Controller\AuthorsList;
use App\Home\Controller\Home;
use App\Home\Controller\Works;
use HttpSoft\Response\HtmlResponse;

return [
    'home'          => ['/', Home::class],
    'about'         => ['/about/{action}', About::class],
    'works'         => ['/works/{action?}', Works::class],
    'authors'       => ['/authors', AuthorsList::class],

    'author'        => ['/author/{action?}/{id}', Author::class],
    'author.controls'=>['/author/controls/{id?}', Controls::class],
    'author.form'   => ['/author/form/{id?}', Form::class],
    'author.save'   => ['/author/save/{id?}', [Form::class, 'save']],
];
