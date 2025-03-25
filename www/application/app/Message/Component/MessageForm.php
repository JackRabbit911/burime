<?php declare(strict_types=1);

namespace App\Message\Component;

use Sys\Collection\Collection;
use Sys\Form\Form;

class MessageForm extends Form
{
    public function __construct(Collection $myAuthors, array $recipients,
    int $author_id, ?string $subject = '', ?string $body = '')
    {
        foreach ($myAuthors as $myAuthor) {
            $options[] = [
                'label' => $myAuthor->alias,
                'value' => $myAuthor->id,
                'selected' => ($myAuthor->id == $author_id) ? true : false,
            ];
        }

        $this->title('Message creation form');
        $this->form('message/message_form')
            ->action(path('message', ['action' => 'save']))
            ->id('msgform');
        $this->select('from')
            ->options($options);

        $this->checkbox('important')
            ->label(__('Mark as important'));

        $this->text('subject')
            ->value(__($subject));

        foreach ($recipients as $key => $recipient) {
            $attr = [
                'name' => 'to[]',
                'label' => $recipient->alias,
                'value' => $recipient->id,
                'checked' => true,
            ];

            $this->group($key, 'checkbox', 'to', $attr);
        }

        $this->textarea('data[body]')
            ->label('Message')
            ->value($body)
            ->rows(5);
    }
}
