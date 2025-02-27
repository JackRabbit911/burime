<?php declare(strict_types=1);

namespace App\Branch\Component;

use App\Branch\Branch;
use Sys\Form\Form;

class StatusForm extends Form
{
    public function __construct(Branch $branch)
    {
        $this->set('branch', $branch);

        $this->form('branch/form/status')
            ->action(path('edit.save', ['action' => 'publish', 'id' => $branch->id ?? null]))
            ->id('statusform');

        foreach ($branch->authors as $key => $author)
        {
            $attr = [
                'name' => 'authors[]',
                'id' => 'author' . (string) $key,
                'label' => $author->alias,
                'value' => $author->id,
            ];

            $this->group($key, 'checkbox', 'authors', $attr);
        }

        $this->radio('status')
            ->label('Save with status:')
            ->value($branch->status ?? 0);
    }
}
