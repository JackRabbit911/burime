<?php

declare(strict_types=1);

namespace App\Burime\Component;

use Sys\Template\Form;

class Ask2Join extends Form
{
    protected ?string $view = 'burime/choice_author';

    public function __construct($branch_id, $user)
    {
        $options = $user->ownAuthors->map(fn($author) => [
            'label' => $author->alias,
            'value' => $author->id
        ]);

        $this->data = [
            'form' => [
                'id' => 'ask2join',
                'action' => path('participation', ['branch_id' => $branch_id, 'action' => 'ask2join']),
            ],
            'author' => [
                'name' => 'author',
                'label' => 'Choice Your author',
                'options' => $options,
            ],
        ];
    }
}
