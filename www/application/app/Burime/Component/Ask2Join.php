<?php declare(strict_types=1);

namespace App\Burime\Component;

use Sys\Form\Form;

class Ask2Join extends Form
{
    public function __construct($branch_id, $user)
    {
        foreach ($user->ownAuthors as $author) {
            $options[] = [
                'label' => $author->alias,
                'value' => $author->id,
            ];
        }

        $this->form('burime/choice_author')
            ->id('postform')
            ->action(path('participation', ['branch_id' => $branch_id, 'action' => 'ask2join']));
        
        $this->select('author')
            ->label('Choice Your author')
            ->options($options);
    }
}
