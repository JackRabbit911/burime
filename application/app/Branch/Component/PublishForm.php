<?php declare(strict_types=1);

namespace App\Branch\Component;

use App\Branch\Branch;
use Sys\Form\Form;

class PublishForm extends Form
{
    public function __construct(Branch $branch)
    {
        $route = (isset($branch->id)) ? 'edit.save' : 'create.save';

        $this->set('branch', $branch);

        $this->form('branch/create/publish')
            ->action(path($route, ['action' => 'publish', 'id' => $branch->id ?? null]))
            ->id('publishform');

        $this->checkbox('invitation')
            ->label('Send invitation to participants')
            ->checked(true)
            ->value(true);

        $this->hidden('first_id')
            ->value(null);

        $this->hidden('last_id')
            ->value(null);

        $this->textarea('first_post')
            ->label(__('First post'))
            ->value('')
            ->rows(5);
            
        $this->textarea('last_post')
            ->label(__('Last post'))
            ->value('')
            ->rows(5);
    }

    public function ready($branch)
    {
        $ready = 0;

        if (isset($branch->genres) && !empty($branch->genres)) {
            $ready += 30;
        }

        if (isset($branch->role)) {
            $ready += 4;
        }

        if (isset($branch->info['post_size']) && isset($branch->info['time_limit'])) {
            $ready += 4;
        }

        if (isset($branch->title) && !empty($branch->title)) {
            $ready += 16;
        }

        if (isset($branch->info['description']) && !empty($branch->info['description'])) {
            $ready += 2;
        }

        if (isset($branch->info['rules']) && !empty($branch->info['rules'])) {
            $ready += 2;
        }

        if (isset($branch->authors) && !empty($branch->authors)) {
            $ready += 30;
        }

        if (isset($branch->cover) && !empty($branch->cover)) {
            $ready += 10;
        }

        return $ready;
    }
}
