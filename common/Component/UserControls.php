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
            $this->data['badge'] = ($countMsg['new']) ? '+' . $countMsg['new'] : false;
            $this->data['href'] = path('my');
        }
    }

    public function render()
    {
        return view('common/user_controls', $this->data);
    }
}
