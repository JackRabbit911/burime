<?php declare(strict_types = 1);

namespace Auth\Model;

use Sys\Image\Im;

class AvatarRepo
{
    public function save($uploadedFile, $user_id)
    {
        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            return;
        }

        $config = config('user');

        if (!is_dir($config['avatar_path'])) {
            mkdir($config['avatar_path'], 0777);
        }

        if (!is_writable($config['avatar_path'])) {
            chmod($config['avatar_path'], 0777);
        }

        $avatarPath = $config['avatar_path'] . $user_id;

        foreach (glob($avatarPath . '.*') as $fn) {
            unlink($fn);
        }

        Im::create($uploadedFile)
            ->thumb($config['avatar_size'])
            ->save($avatarPath);
    }
}
