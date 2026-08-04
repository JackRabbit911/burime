<?php

use Adm\Service\ADM;
use Adm\Service\MenuItem;

return [
    MenuItem::create('Pages', 'pages', ADM::CONTENT),
    MenuItem::create('Users', 'users', ADM::USERS),
    MenuItem::create('Tests', 'testss', ADM::DEVELOP),
    MenuItem::create('Deploy', 'deploy', ADM::DEVELOP),
    MenuItem::create('SEO', 'seo', ADM::SEO),
    MenuItem::create('Translate', 'translate', ADM::TRANSLATE),
    MenuItem::create('Burime', 'burime', ADM::BURIME)
        ->sub(
            MenuItem::create('Works', 'works'),
            MenuItem::create('Authors', 'authors'),
        ),
    MenuItem::create('SuperAdmin', 'super', ADM::ADMIN),
    MenuItem::create('Commerce', 'commerce', ADM::COMMERCE)
];
