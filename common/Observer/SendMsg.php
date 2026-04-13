<?php declare(strict_types=1);

namespace Common\Observer;

use App\Message\SendMsg as MessageSendMsg;
use Attribute;

#[Attribute]
class SendMsg extends MessageSendMsg {}
