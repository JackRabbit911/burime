<?php declare(strict_types=1);

namespace App\Author\Repo;

use App\Author\Author;
use Sys\Helper\Facade\File;
use Modules\Image\Im;

class Avatar
{
    public function save($file, $author_id)
    {
        if ($file->getError() !== UPLOAD_ERR_OK) {
            return false;
        }

        $avatarPath = Author::AUTHOR_AVATAR_PATH;

        $avatarPath = Author::AUTHOR_AVATAR_PATH . $author_id;

        foreach (glob($avatarPath . '.*') as $fn) {
            unlink($fn);
        }

        $mime = $file->getClientMediaType();
        $ext = File::extByImageType($mime);

        $avatarPath .= $ext;
        $file->moveTo($avatarPath);

        chmod($avatarPath, 0666);
        
        $im = new Im($avatarPath);
        $im->thumb(200)->save();

        return true;
    }
}
