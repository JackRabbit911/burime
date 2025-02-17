<?php declare(strict_types=1);

namespace App\Branch\Component;

use Sys\Form\Form;

class AuthorsChoice extends Form
{
    public function __construct($data, $branch = null)
    {
        $route = (isset($branch->id)) ? 'edit.save' : 'create.save';
        $this->set($data);

        $master_id = (isset($data['master'])) ? $data['master']->id : 0;

        foreach ($data['myAuthors'] as $myAuthor) {
            $options[] = [
                'label' => $myAuthor->alias,
                'value' => $myAuthor->id,
                'selected' => ($myAuthor->id == $master_id) ? true : false,
            ];
        }

        $this->form('web/create/authors_form')
            ->action(path($route, ['action' => 'authors', 'id' => $branch->id ?? null]))
            ->id('authorsform');

        $this->select('master')
            ->label('Team leader:')
            ->options($options);

        foreach ($data['invites'] as $key => $invite) {
            $attr = [
                'name' => 'author[]',
                'id' => 'author-' . $invite->id,
                'label' => $invite->alias,
                'value' => $invite->id,
                'checked' => true,
            ];

            $this->group($key, 'checkbox', 'invites', $attr);
        }
    }
}
