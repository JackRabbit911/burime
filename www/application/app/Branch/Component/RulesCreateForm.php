<?php declare(strict_types=1);

namespace App\Branch\Component;

use App\Branch\Branch;
use Sys\Form\Form;

class RulesCreateForm extends Form
{
    public function __construct(Branch $branch)
    {
        $this->set('id', $branch->id ?? null);

        $this->form('branch/form/rules')
            ->action(path('create.save', ['action' => 'rules']))
            ->id('rulesform');

        $this->radio('role')
            ->label('Read/Write mode')
            ->value($branch->role ?? 0);

        $this->checkbox('info[moderation]')
            ->label(__('Pre-moderation'))
            ->checked($this->isTrue($branch->info['moderation'] ?? false))
            ->value(true);

        $this->checkbox('info[comments]')
            ->label(__('Allow comments'))
            ->checked($this->isTrue($branch->info['comments'] ?? true))
            ->value(true);

        $this->checkbox('info[signature]')
            ->label(__('Author`s signature under the post'))
            ->checked($this->isTrue($branch->info['signature'] ?? false))
            ->value(true);

        $this->number('age_limit')
            ->label('Age limit')
            ->value($branch->age_limit ?? 0)
            ->min(0)
            ->max(21)
            ->step(3);

        $this->number('info[post_size]')
            ->label('Post size')
            ->alt_label('(count words)')
            ->value($branch->info['post_size'] ?? 200)
            ->min(50)
            ->max(2000)
            ->step(50);

        $this->number('info[time_limit]')
            ->label('Time limit')
            ->alt_label('(in minutes)')
            ->value($branch->info['time_limit'] ?? 120)
            ->min(30)
            ->max(720)
            ->step(30);

        $this->text('title')
            ->alt_label('Up to 5 words')
            ->value($branch->title ?? '');

        $this->textarea('info[description]')
            ->label(__('Desctiption'))
            ->alt_label('Up to 200 words')
            ->value($branch->info['description'] ?? '')
            ->rows(5);
            
        $this->textarea('info[rules]')
            ->label(__('Private rules'))
            ->alt_label('Up to 200 words')
            ->value($branch->info['rules'] ?? '')
            ->rows(5);
    }
}
