<?php declare(strict_types=1);

namespace App\Author\Repo;

class Statistic
{
    public function get($author_id)
    {
        return [
            'books' => [
                'title' => 'Books',
                'count' => 3,
                'href' => '',
                'title_link' => 'Authors books',
            ],
            'posts' => [
                'title' => 'Posts',
                'count' => 13,
                'href' => '',
                'title_link' => 'All posts by author',
            ],
            'comments' => [
                'title' => 'Comments',
                'count' => 128,
                'href' => '',
                'title_link' => 'Authors comments',
            ],
            'subscribers' => [
                'title' => 'Subscribers',
                'count' => 17,
                'href' => '',
                'title_link' => 'Show subscribers',
            ],
            'in_groups' => [
                'title' => 'In groups',
                'count' => 8,
                'href' => '',
                'title_link' => 'Go to groups',
            ],
        ];
    }
}
