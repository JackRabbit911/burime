<?php declare(strict_types = 1);

return [
    [
        'title' => 'a new project',
        'href' => path('about.create', ['action' => 'new_branch']),
    ],
    [
        'title' => 'a branch in the project',
        'href' => path('about.create', ['action' => 'child_branch']),
    ],
    [
        'title' => 'an author',
        'href' => '',
    ],
    [
        'title' => 'a group',
        'href' => '',
    ],
];
