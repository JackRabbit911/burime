<?php

declare(strict_types=1);

namespace Adm\Service;

use Override;
use Sys\Entity\DTO;

class UserDTO
{
    public readonly int $id;
    public readonly string $name;
    public readonly ?string $email;
    public readonly ?string $phone;
    public readonly ?int $role;
    public readonly ?string $dob;
    public readonly ?int $sex;
    public readonly ?string $created;
    public readonly ?string $avatarUrl;

    private string $path = DOCROOT . 'avatar/user/';

    public function __construct()
    {
        $pattern = $this->path . $this->id . '.{jpg,jpeg,png,gif,webp}';
        $file = glob($pattern, GLOB_BRACE)[0] ?? '';

        if (is_file($file)) {
            $this->avatarUrl = ltrim($file, '.');
        } else {
            $this->avatarUrl = null;
        }
    }
}
