<?php

declare(strict_types=1);

namespace Common\Controller;

use Sys\Controller\BaseController;
use Sys\Response\FileResponse;

class Avatar extends BaseController
{
    private const NO_AVATAR = 'avatar/no_avatar.webp';
    private const AVATAR_URL = 'avatar/';
    
    public function author(int|string $id, int|string $lifetime = 0)
    {
        $file = $this->getAvatarById((int) $id, 'author');

        return new FileResponse($file, (int) $lifetime);
    }

    public function user(int|string $id, int|string $lifetime = 0)
    {
        $file = $this->getAvatarById((int) $id, 'user');

        return new FileResponse($file, (int) $lifetime);
    }

    private function getAvatarById(int $id, string $folder)
    {
        $avatar_path = DOCROOT . self::AVATAR_URL . $folder . '/';
        $pattern = $avatar_path . $id . '.{jpg,jpeg,png,gif,webp}';
        $file = glob($pattern, GLOB_BRACE)[0] ?? '';

        return is_file($file) ? $file : DOCROOT . self::NO_AVATAR;
    }
}
