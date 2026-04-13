<?php

declare(strict_types=1);

namespace App\Api\Message\Repository;

use App\Api\Message\Enum\MsgStatus;
use App\Api\Message\Model\ModelSaveMessage;

class SaveRepo
{
    public function __construct(
        private ModelSaveMessage $model
    ) {}

    public function save(array $data)
    {
        $message_id = $this->saveMessage($data['message']);
        $this->saveRecipients($data['recipients'], $message_id, $data['important']);

        return $message_id;
    }

    private function saveMessage(array $message)
    {
        $message['data'] = json_encode($message['data'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return $this->model->saveMessage($message);
    }

    private function saveRecipients(array $data, int $message_id, int $important)
    {
        $recipients = array_map(function($value) use ($message_id, $important) {
            return [
                'message_id' => $message_id,
                'author_id' => $value,
                'status' => $important ? MsgStatus::Important->value : MsgStatus::New->value,
            ];
        }, $data);

        $this->model->saveRcipients($recipients);

        return $recipients;
    }
}
