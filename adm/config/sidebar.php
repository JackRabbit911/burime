<?php

use Adm\Service\ADM;
use Adm\Service\MenuItem;

return [
    MenuItem::create('Pages', 'pages', 'book_open_text', ADM::CONTENT),
    MenuItem::create('Users', 'users', 'users', ADM::USERS),
    MenuItem::create('Tests', 'tests', 'bug_play', ADM::DEVELOP),
    MenuItem::create('Develop', 'develop', 'code', ADM::DEVELOP)
        ->sub(
            MenuItem::create('Database', 'database', 'database'),
            MenuItem::create('Cron', 'cron', 'calendar_clock'),
            MenuItem::create('Worker', 'worker', 'square_stack'),
            MenuItem::create('Clean', 'clean', 'broom')
        ),
    MenuItem::create('Deploy', 'eploy', 'hard_drive_download', ADM::DEVELOP),
    MenuItem::create('SEO', 'seo', 'code_xml', ADM::SEO),
    MenuItem::create('Translate', 'translate', 'languages', ADM::TRANSLATE),
    MenuItem::create('Burime', 'burime', 'trophy', ADM::BURIME)
        ->sub(
            MenuItem::create('Works', 'works', 'book_open_text'),
            MenuItem::create('Authors', 'authors', 'users'),
        ),
    MenuItem::create('SuperAdmin', 'super', 'user_star', ADM::ADMIN),
    MenuItem::create('Commerce', 'commerce', 'dollar_sign', ADM::COMMERCE)
];
