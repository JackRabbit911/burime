<?php

declare(strict_types=1);

namespace App\Burime\Component;

use Sys\Template\Form;

class PostForm extends Form
{
    protected ?string $view = 'burime/form';

    public function __construct($data, $post_last, $post_current)
    {
        $branch = $data['branch'];
        [$macros, $author] = $this->author($data['myAuthors']);
        
        $this->data = [
            'branch' => $branch,
            'last' => $post_last,
            'postPermissions' => $data['postPermissions'],
            'timer' => $data['timer'],
            'macros' => $macros,
            'title' => $data['title'],
            'form' => [
                'id' => 'postform',
                'action' => path('branch.post', ['branch_id' => $branch->id, 'post_id' => $post_current->id ?? null]),
            ],
            'new_post' => [
                'name' => 'new_post',
                'label' => $post_current ? __('Current post') : __('New post'),
                'alt_label' => __('Up to count words',['count' => $branch->info['post_size']]),
                'placeholder' => __('Enter Your imperishable work'),
                'rows' => 6,
                'value' => $post_current ? $post_current->body : '',
            ],
            'author' => $author,
        ];
    }

    private function author(mixed $myAuthors): array
    {
        if (is_iterable($myAuthors)) {
            foreach ($myAuthors as $myAuthor) {
                $options[] = [
                    'label' => $myAuthor->alias,
                    'value' => $myAuthor->id,
                ];
            }

            $macros = 'select';
            $author = ['name' => 'author', 'options' => $options];
        } else {
            $macros = 'input';
            $author = [
                'name' => 'author',
                'type' => 'hidden',
                'label' => $myAuthors->alias,
                'value' => $myAuthors->id,
            ];
        }

        return [$macros, $author];
    }
}
