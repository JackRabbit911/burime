<?php declare(strict_types=1);

namespace App\Message;

use App\Message\Model\ModelMessage;
use App\Message\Msg;
use Sys\Observer\Interface\ObserverInterface;
use Attribute;

#[Attribute]
class SendMsg implements ObserverInterface
{
    private $object;

    public function update(string|object $object): self
    {
        $this->object = is_string($object) ? container()->get($object) : $object;
        return $this;
    }

    public function handle(): void
    {
        Msg::fromArray($this->object->msg)
            ->save(ModelMessage::class);
    }
}
