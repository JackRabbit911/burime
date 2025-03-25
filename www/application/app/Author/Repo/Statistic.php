<?php declare(strict_types=1);

namespace App\Author\Repo;

use App\Author\Model\ModelStat;

class Statistic
{
    private ModelStat $model;

    public function __construct(ModelStat $model)
    {
        $this->model = $model;
    }

    public function get($author)
    {
        $data = $this->model->getStat($author->id);
        
        return [
            'members' => [
                'title' => 'Members',
                'count' => $author->c_members,
                'href' => path('author', ['action' => 'members', 'id' => $author->id]),
                'title_link' => 'Go to members',
            ],
            'rating' => [
                'title' => 'Rating',
                'count' => round($data['rating'], 2),
            ],
            'books' => [
                'title' => 'Books',
                'count' => $data['books'],
                'href' => '',
                'title_link' => 'Authors books',
            ],
            'posts' => [
                'title' => 'Posts',
                'count' => $data['posts'],
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
