<?php declare(strict_types=1);

namespace App\Message;

use App\Message\Model\ModelMessage;
use Sys\Entity\Entity;
use Sys\Trait\FromArray;
use Sys\Trait\ToArray;
use Exception;
use JSON_UNESCAPED_SLASHES;
use JSON_UNESCAPED_UNICODE;

use function DI\factory;

#[ModelMessage]
class Msg extends Entity
{
    use FromArray {fromArray as factory;}
    use ToArray;

    private string $tpl;
    private array $to = [];
    private int $from;
    private $important;
    private string $subject;
    private string|array $data;
    private string $view;
    private string $path = 'messages/';

    // public function __construct()
    // {
    //     if (isset($this->data) && !is_array($this->data)) {
    //         $this->data = json_decode($this->data, true);
    //     }

    //     if (isset($this->tpl)) {
    //         $this->tpl($this->tpl);
    //     }
    // }

    public function send(?string $model = null)
    {
        $this->save($model);
    }

    public static function fromArray(array $data): self
    {
        return (isset($data['tpl']))
            ? self::factory($data)->tpl($data['tpl'])
            : self::factory($data);
    }

    public function prepareProps()
    {
        if (isset($this->data)) {
            $this->data = json_encode($this->data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }

        return $this;
    }

    public function tpl(string $tpl): self
    {
        $settings = config($this->path . $tpl);

        if (!$settings) {
            throw new Exception(sprintf('Config %s not found', $tpl));
        }

        foreach ($settings as $key => $value) {
            call_user_func([$this, $key], $value);
        }

        return $this;
    }

    public function to(int|array $to): self
    {
        if (int ($to)) {
            $to = [$to];
        }

        $this->to = array_merge($this->to, $to);
        return $this;
    }

    public function from(int $from): self
    {
        $this->from = $from;
        return $this;
    }

    public function subject(string $subject)
    {
        $this->subject = $subject;
    }

    public function data(array $data): self
    {
        $this->data = array_replace_recursive($this->data, $data);
        return $this;
    }

    public function view(string $view): self
    {
        $this->view = $view;
        return $this;
    }
}
