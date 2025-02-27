<?php declare(strict_types=1);

namespace App\Branch\Component;

use App\Branch\Branch;
use Sys\Form\Form;

class CoverForm extends Form
{
    public function __construct(Branch $branch)
    {
        $route = (isset($branch->id)) ? 'edit.save' : 'create.save';

        $this->set('branch', $branch);

        $this->form('branch/form/cover')
            ->action(path($route, ['action' => 'cover', 'id' => $branch->id ?? null]))
            ->id('coverform');

        $this->color('info[bg_color]')
            ->label(__('Background'))
            ->id('bgcolor')
            ->value($branch->info['bg_color'] ?? '#eeeeee');

        $this->color('info[text_color]')
            ->label(__('Text'))
            ->id('textcolor')
            ->value($branch->info['text_color'] ?? '#333333');

        $this->file('cover')
            ->label(__('Upload cover image'))
            ->alt_label(__('Up to 400x600, aspect 2/3'))
            ->value($branch->info->bg_cover ?? null);
    }
}
