<?php

namespace Auth;

use Auth\Model\ModelUser;
use Sys\Contract\UserInterface;
use Sys\Entity\Entity;
use Sys\Trait\FromArray;
use DateTime;

#[ModelUser]
final class User extends Entity implements UserInterface
{
    use FromArray;

    const FEMALE = 0;
    const MALE = 1;

    // const AVATAR_SRC = 'src';
    // const AVATAR_HTML = 'html';
    // const AVATAR_NAME = 'name';

    // const NO_AVATAR = DOCROOT . 'avatar/no_avatar.jpg';
    // const USER_AVATAR_PATH = DOCROOT . 'avatar/user/';
    // const AVATAR_SIZE = 120;

    private string $jsonProp = 'info';

    public function __construct()
    {
        if (isset($this->info)) {
            $this->info = json_decode($this->info, true);
        }
    }

    public function age(string $date = 'today')
    {
        if (!$this->dob) {
            return 0;
        }

        return (new DateTime($this->dob))->diff(new DateTime($date))->y;
    }

    public function prepareProps()
    {
        if (isset($this->info)) {
            $this->info = json_encode($this->info);
        }

        return $this;
    }

    // public function avatar($res = self::AVATAR_SRC)
    // {
    //     $pattern = self::USER_AVATAR_PATH . $this->id . '.{jpg,jpeg,png,gif}';        
    //     $file = glob($pattern, GLOB_BRACE)[0] ?? '';

    //     if ($res === self::AVATAR_NAME) {
    //         return $file;
    //     }

    //     if (!is_file($file)) {
    //         $file = self::NO_AVATAR;
    //     }
        
    //     $file = str_replace('//', '/', $file);
    //     $src = ltrim($file, '.');    

    //     return ($res === self::AVATAR_SRC) ? $src 
    //             : '<img src="' . $src . '" alt="' . $this->name . '" />';
    // }
}
