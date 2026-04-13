<?php

declare(strict_types=1);

namespace App\Api\Message\Repository;

use App\Api\Message\Model\ModelAuthors;
use App\Api\Message\Model\ModelMessage;
use stdClass;

class BlankRepo
{
    private stdClass $msg;

    public function __construct(
        private ModelAuthors $modelAuthors,
        private ModelMessage $modelMessage
    )
    {
        $this->msg = new stdClass;
        $this->msg->message = new stdClass;
        $this->msg->message->data = new stdClass;
        $this->msg->message->from = null;
        $this->msg->message->subject = '';
        $this->msg->message->data->body = '';
        $this->msg->important = false;
    }

    public function newMsg(array $params)
    {
        $this->msg->recipients = $this->getRecipients($params['to'] ?? null);
        return $this->msg;
    }

    public function reply(array $params)
    {
        $message = $this->modelMessage->findMessage((int) $params['id']);
        $this->msg->message->from = (int) $params['from'] ?? null;
        $this->msg->message->subject = 'Re: ' . $message->subject;
        $this->msg->recipients = $this->getRecipients($message->from);

        return $this->msg;
    }

    public function rewritePost(array $params)
    {
        $this->msg->message->from = (int) $params['from'] ?? null;
        $this->msg->message->subject = __('Edit your post');
        $this->msg->message->data->tpl = 'branch';
        $this->msg->recipients = $this->getRecipients((int) $params['to']);

        return $this->msg;
    }

    private function getRecipients(int|string|array|null $to)
    {
        if (!$to) {
            return [];
        }

        if (is_array($to)) {
            return $this->modelAuthors->getAuthorsByIds($to);
        }

        return [$this->modelAuthors->findAuthor($to)];
    }
}
