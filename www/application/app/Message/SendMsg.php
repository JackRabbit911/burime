<?php declare(strict_types=1);

namespace App\Message;

use Attribute;
use Sys\Observer\Interface\Observer;

#[Attribute]
class SendMsg implements Observer
{
    private object $handler;
    private $object;

    public function __construct(string $handler)
    {
        $this->handler = container()->get($handler);
    }

    public function update(object|string|callable $object): self
    {
        $this->object = $object;
        return $this;
    }

    public function handle()
    {
        if (is_string($this->object)) {
            $this->object = container()->get($this->object);
        }

        call_user_func_array([$this->handler, 'send'], $this->object->msgData);
    }
}
