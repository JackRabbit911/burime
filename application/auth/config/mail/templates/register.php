<?php

return [
    'view' => '@auth/email/message',
    'subject' => 'Registration confirmation',
    'mailbox' => env('MAIL_BOX_123'),
    'data' => [
        'title' => 'Registration confirmation',
        'appeal' => 'Dear, username!',
        'msg' => 'msg_register',
        'link' => [
            'href' => url('register', ['action' => 'confirm', 'code' => 'code']),
            'title' => 'this link',
        ],
    ],
];
