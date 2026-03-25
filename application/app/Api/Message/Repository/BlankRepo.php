<?php

declare(strict_types=1);

namespace App\Api\Message\Repository;

use App\Api\Message\Model\ModelAuthors;
use App\Api\Message\Model\ModelMessage;

class BlankRepo
{
    public function __construct(
        private ModelAuthors $modelAuthors,
        private ModelMessage $modelMessage
    ) {}

    public function newMsg()
    {
        return [
            'message' => [
                'from' => null,
                'subject' => '',
                'data' => ['body' => ''],
            ],
            'recipients' => [],
            'important' => false,
        ];
    }

    public function reply(array $params)
    {
        $message = $this->modelMessage->findMessage((int) $params['id']);
        $data = $this->defaultMsg($message->from, (int) $params['from']);
        $data['message']['subject'] = 'Re: ' . $message->subject;
        return $data;
    }

    public function rewritePost(array $params)
    {
        $data = $this->defaultMsg((int) $params['to'], (int) $params['from']);
        $data['message']['subject'] = 'Отредактируйте Ваш пост';
        $data['message']['data']['tpl'] = 'branch';
        return $data;
    }

    private function defaultMsg(int $to, int $from)
    {
        $data['recipients'] = [[
            'id' => $to,
            'alias' => $this->modelAuthors->findAuthor($to),
        ]];

        $data['message'] = [
            'from' => $from,
            'subject' => 'Отредактируйте Ваш пост',
            'data' => [
                'body' => '',
                'tpl' => '',
            ],
        ];

        $data['important'] = false;

        return $data;
    }
}
