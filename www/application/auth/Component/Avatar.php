<?php declare(strict_types=1);

namespace Auth\Component;

use Auth\User;
use Sys\Template\Component;

class Avatar extends Component
{
    private array $data;
    private string $view = '@auth/common/avatar';

    public function __construct(User $user)
    {
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
        return view($this->view, $this->data);
    }
}
