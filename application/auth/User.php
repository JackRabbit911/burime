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

    // const FEMALE = 0;
    // const MALE = 1;

    // protected string $name;
    // protected string $email;
    protected ?string $dob = null;
    protected ?int $sex = null;

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
}
