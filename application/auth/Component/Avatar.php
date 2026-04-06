<?php

declare(strict_types=1);

namespace Auth\Component;

use Auth\User;
use Sys\Template\Component;
use Sys\Template\TemplateInterface;

class Avatar extends Component
{
    private static array $default = [
        'avatar_size' => 120,
        'avatar_path' => DOCROOT . 'avatar/user/',
        'no_avatar' => DOCROOT . 'avatar/no_avatar.jpg',
    ];

    private array $data;

    public function __construct(TemplateInterface $tpl, User $user, ?int $size = null)
    {
        $tpl->addPath(APPPATH . 'auth/views', 'auth');
        $this->data['src'] = self::getSrc($user->id);
        $this->data['alt'] = $user->name;
        $this->data['size'] = $size;
    }

    public static function getSrc(int $user_id): string
    {
        static $config;

        if (!$config) {
            $config = config('user') ?? self::$default;
        }

        $path = $config['avatar_path'] . $user_id;
        $pattern = $path . '.{jpg,jpeg,png,gif}';

        $file = glob($pattern, GLOB_BRACE)[0] ?? null;
        $file = $file ?: $config['no_avatar'];

        return str_replace(DOCROOT, '/', $file);
    }

    public function render()
    {
        return view('@auth/common/avatar', $this->data);
    }
}
