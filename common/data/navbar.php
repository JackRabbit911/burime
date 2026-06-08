<?php declare(strict_types = 1);

return [
    [
        'title' => 'Home',
        'href' => path('home'),
    ],
    [
        'title' => 'About',
        'href' => 'about',
        'sub' => [
            [
                'title' => 'What is burime',
                'href' => path('about', ['action' => 'burime']),
            ],
            [
                'title' => 'Glossary',
                'href' => path('about', ['action' => 'glossary']),
            ],
            [
                'title' => 'Rules of the game',
                'href' => path('about', ['action' => 'rules']),
            ],
            [
                'title' => 'About genres',
                'href' => path('about', ['action' => 'genres']),
                'border' => true,
            ],
        ],
    ],
    [
        'title' => 'Works',
        'href' => path('home', ['action' => 'works']),
    ],
    [
        'title' => 'Authors',
        'href' => path('home', ['action' => 'authors']),
    ],
];
