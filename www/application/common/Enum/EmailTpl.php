<?php declare(strict_types=1);

namespace Common\Enum;

enum EmailTpl: int
{
    case Register = 1;
    case Restore = 2;

    public function getConfig()
    {
        $config = 'mail/templates/' . lcfirst($this->name);
        return config($config);
    }
}
