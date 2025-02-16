<?php

return [
    'restore' => [
        'info' => [
            'role' => 'info',
            'subject' => 'Login recovery',
            'appeal' => 'Dear, username!',
            'msg' => 'info_restore',
        ],
        'success' => [
            'role' => 'success',
            'subject' => 'Congratulations!',
            'appeal' => 'Dear, username!',
            'msg' => 'Your password has been successfully changed.',
            'btn' => [
                'href' => path('auth', ['action' => 'form']),
                'title' => 'Welcome to Sign In!',
            ],
        ],
        'whoops' => [
            'role' => 'warning',
            'subject' => 'Whoops...',
            'msg' => 'whoops_restore',
            'link' => [
                'href' => path('restore.password', ['action' => 'form']),
                'title' => 'password change procedure',
            ],
        ],
    ],
    'password' => [
        'success' => [
            'role' => 'success',
            'subject' => 'Congratulations!',
            'appeal' => 'Dear, username!',
            'msg' => 'pass_changed',
            'btn' => [
                'href' => path('auth', ['action' => 'form']),
                'title' => 'Welcome to Sign In!',
            ],
        ],
    ],
    'register' => [
        'info' => [
            'role' => 'info',
            'subject' => 'Registration confirmation',
            'appeal' => 'Dear, username!',
            'msg' => 'info_register',
        ],
        'success' => [
            'role' => 'success',
            'subject' => 'Congratulations!',
            'appeal' => 'Dear, username!',
            'msg' => 'You have successfully registered!',
            'btn' => [
                'href' => path('auth', ['action' => 'form']),
                'title' => 'Welcome to Sign In!',
            ],
        ],
        'whoops' => [
            'role' => 'warning',
            'subject' => 'Whoops...',
            'msg' => 'whoops_register',
            'link' => [
                'href' => path('register', ['action' => 'form']),
                'title' => 'registration procedure',
            ],
        ],
    ],
];
