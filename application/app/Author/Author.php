<?php declare(strict_types=1);

namespace App\Author;

use App\Author\Model\ModelAuthor;
use Common\Contract\AuthorInterface;
use Sys\Entity\Entity;

#[ModelAuthor]
class Author extends Entity implements AuthorInterface
{
    const AVATAR_SRC = 'src';
    const AVATAR_HTML = 'html';
    const AVATAR_NAME = 'name';
    const NO_AVATAR = '/avatar/no_avatar.jpg';
    const AVATAR_URL = 'avatar/author/';
    const AVATAR_PATH = DOCROOT . self::AVATAR_URL;
    const AVATAR_SIZE = 120;

    public function __construct()
    {
        if (isset($this->info)) {
            $this->info = json_decode($this->info, true);
        }

        if (isset($this->o_members)) {
            $this->o_members = json_decode($this->o_members, true);
        }

        if (isset($this->j_members)) {
            $this->j_members = json_decode($this->j_members, true);
        }
    }

    public function prepareProps()
    {
        if (isset($this->info)) {
            $this->info = json_encode($this->info);
        }

        return $this;
    }

    public function avatar($res = self::AVATAR_SRC)
    {
        $author_id = (isset($this->id)) ? $this->id : 0;
        $alt = (isset($this->alias)) ? $this->alias : 'New author';

        return self::getAvatarById($author_id, $alt, $res);
    }

    public static function getAvatarById($id, $alt = '', $res = self::AVATAR_SRC)
    {
        $pattern = self::AVATAR_PATH . $id . '.{jpg,jpeg,png,gif}';
        $file = glob($pattern, GLOB_BRACE)[0] ?? '';

        if ($res === self::AVATAR_NAME) {
            return $file;
        }

        if (!is_file($file)) {
            $src = self::NO_AVATAR;
        } else {
            $src = '/' . self::AVATAR_URL . pathinfo($file)['basename'];
        }

        
        // $file = str_replace('//', '/', $file);
        // $src = ltrim($file, '.');    

        return ($res === self::AVATAR_SRC) ? $src 
                : '<img src="' . $src . '" alt="' . $alt . '" />';
    }
}
