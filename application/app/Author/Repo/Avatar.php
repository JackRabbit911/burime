<?php declare(strict_types=1);

namespace App\Author\Repo;

use App\Author\Author;
// use Sys\Helper\Facade\File;
// use Modules\Image\Im;
use Sys\Image\Im;

class Avatar
{
    public function save($uploadedFile, $author_id)
    {
        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            return;
        }

        if (!is_dir(Author::AVATAR_PATH)) {
            mkdir(Author::AVATAR_PATH, 0777);
        }

        if (!is_writable(Author::AVATAR_PATH)) {
            chmod(Author::AVATAR_PATH, 0777);
        }

        $avatarPath = Author::AVATAR_PATH . $author_id;

        foreach (glob($avatarPath . '.*') as $fn) {
            unlink($fn);
        }

        Im::create($uploadedFile)
            ->thumb(Author::AVATAR_SIZE)
            ->save($avatarPath);


        // if ($file->getError() !== UPLOAD_ERR_OK) {
        //     return false;
        // }

        // $avatarPath = Author::AUTHOR_AVATAR_PATH;

        // $avatarPath = Author::AUTHOR_AVATAR_PATH . $author_id;

        // foreach (glob($avatarPath . '.*') as $fn) {
        //     unlink($fn);
        // }

        // $mime = $file->getClientMediaType();
        // $ext = File::extByImageType($mime);

        // $avatarPath .= $ext;
        // $file->moveTo($avatarPath);

        // chmod($avatarPath, 0666);

        // Im::create($uploadedFile)
        //     ->thumb($config['avatar_size'])
        //     ->save($avatarPath);
        
        // $im = new Im($avatarPath);
        // $im->thumb(200)->save();

        // return true;
    }
}
