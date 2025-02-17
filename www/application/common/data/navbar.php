<?php declare(strict_types = 1);

return [
    [
        'title' => 'About',
        'href' => 'about',
        'sub' => [
            [
                'title' => 'What is burime',
                'href' => path('about', ['action' => 'burime']),
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
        'href' => path('works'),
    ],
    [
        'title' => 'Authors',
        'href' => path('authors'),
    ],
    [
        'title' => 'Search',
        'href' => '', //path('search'),
    ],
];
