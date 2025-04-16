<?php declare(strict_types=1);

namespace App\Message;

use App\Message\Model\ModelMessage;
use App\Message\Msg;
use Sys\Observer\Interface\Listener;
use Psr\Container\ContainerInterface;
use Attribute;

#[Attribute]
class SendMsg implements Listener
{
    private $object;

    public function __construct(ContainerInterface $c, string $_class)
    {
        $this->object = $c->get($_class);
    }

    public function handle()
    {
        Msg::fromArray($this->object->msg)
            ->save(ModelMessage::class);
    }
}
