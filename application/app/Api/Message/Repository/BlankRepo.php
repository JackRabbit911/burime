<?php

declare(strict_types=1);

namespace App\Api\Message\Repository;

use App\Api\Message\Model\ModelAuthors;
use App\Api\Message\Model\ModelMessage;

class BlankRepo
{
    public function __construct(private ModelAuthors $model) {}

    public function reply(ModelMessage $model, int $id, int $from)
    {
        $message = $model->findMessage($id);
        $data = $this->defaultMsg($message['from'], $from);
        $data['message']['subject'] = 'Re: ' . $message['subject'];
        return $data;
    }

    public function rewritePost(int $to, int $from)
    {
        $data = $this->defaultMsg($to, $from);
        $data['message']['subject'] = 'Отредактируйте Ваш пост';
        $data['message']['data']['tpl'] = 'branch';
        return $data;
    }

    private function defaultMsg(int $to, int $from)
    {
        $data['recipients'] = [[
            'id' => $to,
            'alias' => $this->model->findAuthor($to),
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
