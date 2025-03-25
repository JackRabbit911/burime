<?php

return [
    'view' => '@auth/email/message',
    'subject' => 'Access recovery',
    'mailbox' => env('MAIL_BOX_123'),
    'data' => [
        'title' => 'Login recovery',
        'appeal' => 'Dear, username!',
        'msg' => 'msg_restore',
        'link' => [
            'href' => url('restore', ['action' => 'confirm', 'code' => 'code']),
            'title' => 'this link',
        ],
    ],
];
