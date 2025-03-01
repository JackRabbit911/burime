<?php declare(strict_types=1);

namespace App\Message;

use Sys\Entity\Entity;
use Sys\Trait\FromArray;
use JSON_UNESCAPED_SLASHES;
use JSON_UNESCAPED_UNICODE;
use Sys\Trait\ToArray;

class Msg extends Entity
{
    use FromArray;
    use ToArray;

    private array $to;
    private int $from;
    private $important;
    private string $subject;
    private string|array $data;
    private string $view;

    public function __construct()
    {
        if (isset($this->data) && !is_array($this->data)) {
            $this->data = json_decode($this->data, true);
        }
    }

    public function prepareProps()
    {
        if (isset($this->data)) {
            $this->data = json_encode($this->data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }

        return $this;
    }

    public function to(array $to)
    {
        $this->to = $to;
    }

    public function from(int $from)
    {
        $this->from = $from;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    public function data(array $data)
    {
        $this->data = $data;
    }

    public function view(string $view)
    {
        $this->view = $view;
    }
}
