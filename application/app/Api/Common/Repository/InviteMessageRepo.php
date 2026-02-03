<?php

declare(strict_types=1);

namespace App\Api\Common\Repository;

use App\Api\Message\Enum\MsgStatus;
use App\Api\Message\Model\ModelSaveMessage;
use Common\Enum\BranchAuthorPermissions;
use Common\Enum\BranchAuthorStatus;

class InviteMessageRepo
{
    public function __construct(private ModelSaveMessage $model){}

    public function sendInviteToBranch(array $post, $branch_id)
    {
        $branch = $post['branch'];
        $members = $post['members'];

        $recipients = array_filter($members, fn($v) => $v['status'] === BranchAuthorStatus::invited->value);
        $sender = $this->getSender($members);

        foreach ($recipients as $item) {
            $keys = BranchAuthorPermissions::getRolesArray($item['role']);
            $roles_string = implode(', ', array_map(fn($v) => __($v), $keys));

            $data['body'] = 'Приглашаю в проект: "' . $branch['title'] . '" со следующим набором прав: ' . $roles_string;
            $data['tpl'] = 'inviteToBranch';
            $data['branch'] = $branch_id;
            $data['appeal'] = 'Ув., ' . $item['alias'] . '!';

            $message['from'] = $sender['author_id'];
            $message['data'] = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $message['subject'] = 'Приглашение в проект "' . $branch['title'] . '"';

            $message_id = $this->model->saveMessage($message);

            $recipients = [
                'message_id' => $message_id,
                'author_id' => $item['author_id'],
                'status' => MsgStatus::New->value,
            ];

            $this->model->saveRcipients($recipients);
        }
    }

    private function getSender(array $members)
    {
        return array_reduce($members, function ($carry, $item) {
            if ($item['role'] > $carry['role']) {
                $carry = $item;
            }

            return $carry;
        }, ['role' => 0]);
    }
}
