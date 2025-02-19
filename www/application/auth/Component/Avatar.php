<?php declare(strict_types=1);

namespace Auth\Component;

use Auth\User;
use Sys\Template\Component;
use Sys\Template\TemplateInterface;

class Avatar extends Component
{
    private array $data;

    public function __construct(TemplateInterface $tpl, User $user)
    {
        $tpl->addPath(APPPATH . 'auth/views', 'auth');

        $config = config('user');

        $path = $config['avatar_path'] . $user->id;
        $pattern = $path . '.{jpg,jpeg,png,gif}';

        $file = glob($pattern, GLOB_BRACE)[0] ?? null;
        $file = $file ?: $config['no_avatar'];

        $this->data['src'] = str_replace(DOCROOT, '/', $file);
        $this->data['alt'] = $user->name;
    }

    public function render()
    {
        return view('@auth/common/avatar', $this->data);
    }
}
