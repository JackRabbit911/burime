<?php declare(strict_types=1);

namespace App\Burime\Component;

use Sys\Form\Form;

class PostForm extends Form
{
    public function __construct($data, $post_last, $post_current)
    {
        $branch = $data['branch'];
        $myAuthors = $data['myAuthors'];

        $this->set('branch', $branch);
        $this->set('last', $post_last);

        $this->form('web/branch/form')
            ->id('postform')
            ->action(path('branch.post', 
                [
                    'branch_id' => $branch->id, 
                    'action' => 'save', 
                    'post_id' => $post_current->id ?? null
                ]));
        
        $textarea = $this->textarea('new_post')
            ->label(__('New post'))
            ->alt_label('Up to ' . $branch->info['post_size'] . ' words')
            ->placeholder(__('Enter Your imperishable work'))
            ->rows(6);

        if ($post_current) {
            $textarea->label(__('Current post'))
                ->value($post_current->body);
        }

        if (is_iterable($myAuthors)) {
            foreach ($myAuthors as $myAuthor) {
                $options[] = [
                    'label' => $myAuthor->alias,
                    'value' => $myAuthor->id,
                ];
            }
            
            $this->select('author')
                ->label(null)
                ->options($options);

            $this->set('macros', 'select');
        } else {
            $this->hidden('author')
                ->label($myAuthors->alias)
                ->value($myAuthors->id);
            
            $this->set('macros', 'input');
        }
    }
}
