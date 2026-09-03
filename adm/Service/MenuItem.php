<?php

declare(strict_types=1);

namespace Adm\Service;

use JsonSerializable;
use stdClass;

class MenuItem implements JsonSerializable
{
    private stdClass $data;
    private int $access;

    public function __construct(
        string $label,
        ?string $to,
        ?string $icon = null,
        ?int $access = null,
    ) {
        $this->data = new stdClass;
        $this->data->label = __($label);
        $this->data->to = $to;

        if ($icon) {
            $this->data->icon = $icon;
        }

        if ($access) {
            $this->access = $access;
        }
    }

    public static function create(
        string $label,
        ?string $to = null,
        ?string $icon = null,
        ?int $access = null
    ) {
        return new self($label, $to, $icon, $access);
    }

    public function sub(...$sub)
    {
        $this->data->sub = $sub;
        return $this;
    }

    public function setDisabled(int $role)
    {
        if (isset($this->access) && !($role & $this->access)) {
            $this->data->disabled = true;
        }
    }

    public function jsonSerialize(): mixed
    {
        return $this->data;
    }
}
