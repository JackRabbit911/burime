<?php

declare(strict_types=1);

namespace App\Api\Private\Repository;

use App\Api\Common\Helper\Facade\UploadFile;
use Sys\Helper\Facade\Dir;
use HttpSoft\Message\UploadedFile;

class UserAvatarSaveRepo
{
    private string $dir = './avatar/user/';

    public function saveFile(?UploadedFile $file, int $user_id)
    {
        if (!$file) {
            return;
        }
        
        Dir::clearByMask($this->dir, "$user_id.*");
        UploadFile::save($file, $this->dir, $user_id);
    }
}
