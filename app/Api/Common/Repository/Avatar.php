<?php

declare(strict_types=1);

namespace App\Api\Common\Repository;

use Sys\Image\Image;
use Sys\Trait\Options;
use Sys\Helper\Facade\Dir;
use HttpSoft\Message\UploadedFile;

class Avatar
{
    use Options;

    const USER = 'user';
    const AUTHOR = 'author';

    private int $size = 120;
    private $authorAvatarPath = DOCROOT . '/avatar/author/';
    private $userAvatarPath = DOCROOT . '/avatar/user/';

    public function __construct()
    {
        $this->options();
    }

    public function save(UploadedFile $uploadedFile, int|string $id, string $path = self::AUTHOR, ?int $size = null)
    {
        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            return;
        }

        if (!$size) {
            $size = $this->size;
        }

        $avatar_path = $path === self::USER ? $this->userAvatarPath : $this->authorAvatarPath; 

        if (!is_dir($avatar_path)) {
            mkdir($avatar_path, 0777);
        }

        Dir::clearByMask($avatar_path, "$id.*");

        $avatar_path .= $id . '.webp';

        Image::create($uploadedFile)
            ->avatar($this->size, $this->size)
            ->save($avatar_path);
    }
}
