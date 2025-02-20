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
            $countMsg = $model->getMsgCount($user->id);
            $msg = $countMsg['new'] . '/' . $countMsg['total'];
            $this->data['badge'] = ($countMsg['new']) ? '+' . $countMsg['new'] : false;
    
            $complete = 50;
    
            switch (true) {
                case $user->phone:
                    $complete += 10;
                case $user->dob:
                    $complete += 20;
                case $user->sex:
                    $complete += 20;
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
                    'href' => '',
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
