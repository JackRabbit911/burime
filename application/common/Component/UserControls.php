<?php declare(strict_types = 1);

namespace Common\Component;

use Common\Model\ModelUserStat;
use Sys\Contract\UserInterface;
use Sys\Template\Component;

class UserControls extends Component
{
    private array $data = [];

    public function __construct(ModelUserStat $model, ?UserInterface $user = null)
    {
        if ($user) {
            $countMsg = $model->getMsgCount($user->ownAuthors->props()->all());
            $msg = $countMsg['new'] . '/' . $countMsg['total'];
            $this->data['badge'] = ($countMsg['new']) ? '+' . $countMsg['new'] : false;
    
            $complete = 50;
    
            switch (true) {
                case $user->dob:
                    $complete += 25;
                case $user->sex:
                    $complete += 25;
            }
    
            $this->data['usermenu'] = [
                [
                    'title' => 'Messages',
                    'href' => path('message', ['action' => 'list']),
                    'badge' => $msg,
                ],
                [
                    'title' => 'My Authors',
                    'href' => path('private', ['action' => 'authors']),
                    'border' => true,
                ],
                [
                    'title' => 'My Books',
                    'href' => path('private', ['action' => 'books']),
                ],
                [
                    'title' => 'Favorites',
                    'href' => '',
                ],
                [
                    'title' => 'Bookmarks',
                    'href' => '',
                ],
                [
                    'title' => 'Profile',
                    'href' => path('profile'),
                    'border' => true,
                    'badge' => $complete . '%',
                ],
                [
                    'title' => 'Logout',
                    'href' => path('auth', ['action' => 'logout']),
                    'border' => true,
                ]
            ];
        }
    }

    public function render()
    {
        return view('common/user_controls', $this->data);
    }
}
