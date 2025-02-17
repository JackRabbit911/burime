<?php declare(strict_types=1);

namespace App\Author\Component;

use Sys\Form\Form;

class AuthorForm extends Form
{
    public function __construct($author, $users_authors)
    {
        $view = (isset($author->id)) ? 'web/author/tab_form' : 'web/author/form';
            
        if (!isset($author->openclosed)) {
            $author->openclosed = 2;
        }

        $this->form($view)
            ->id('authorform')
            ->action(path('author.save', ['id' => $author->id ?? null]));
            // ->action(path('author.post', ['action' => 'save', 'id' => $author->id ?? null]));

        $this->text('alias')
            ->placeholder('Your pen name')
            ->value($author->alias ?? '');

        $this->text('info[slogan]')
            ->label('Slogan')
            ->alt_label('Up to 80 chars')
            ->placeholder('Your creative motto')
            ->value($author->info['slogan'] ?? '');

        $this->text('info[info]')
            ->label('Info')
            ->alt_label('Up to 300 chars')
            ->placeholder('Brief information about the author')
            ->rows(5)
            ->value($author->info['info'] ?? '');

        $this->file('avatar')
            ->label('Pick a file for avatar')
            ->alt_label('Up to 1M');

        $this->select('openclosed')
            ->label('Status')
            ->options([
                [
                    'label' => 'Author',
                    'value' => 2,
                    'selected' => ($author->openclosed === 2) ? true : false,
                ],
                [
                    'label' => 'Closed group',
                    'value' => 1,
                    'selected' => ($author->openclosed === 1) ? true : false,
                ],
                [
                    'label' => 'Open group',
                    'value' => 0,
                    'selected' => ($author->openclosed === 0) ? true : false,
                ],
            ]);

        $members = $this->groupMemberControl($users_authors, $author);

        $this->select('member')
            ->label('Member')
            ->options($this->getMemberOptions($members));

        if (count($members) === 1) {
            $this->visible(' hidden');
        }

        $this->set('author', $author);

        if (isset($author->id)) {
            $this->set('dossier', path('author', ['id' => $author->id]));
        } else {
            $this->set('dossier', '');
        }
    }

    private function groupMemberControl(&$users_authors, $author)
    {
        if (!isset($author->id)) {
            return [];
        }

        foreach ($users_authors as $k => $u_author) {
            if ($u_author->id === $author->id ?? null) {
                unset($users_authors[$k]);
            }

            if ($author->openclosed === 2 && $u_author->owner === $author->owner) {
                unset($users_authors[$k]);
            }
        }

        return $users_authors;
    }

    private function getMemberOptions($members)
    {
        foreach ($members as $k => $member) {
            $opts[$k]['label'] = $member->alias;
            $opts[$k]['value'] = $member->id;
        }

        return (isset($opts)) ? $opts : [];
    }
}
