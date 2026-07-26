<?php

use Adm\Service\MenuItem;

return [
    MenuItem::create('Pages', 'pages', ADM_CONTENT),
    MenuItem::create('Users', 'users', ADM_USERS),
    MenuItem::create('Tests', 'testss', ADM_DEVELOP),
    MenuItem::create('Deploy', 'deploy', ADM_DEVELOP),
    MenuItem::create('SEO', 'seo', ADM_SEO),
    MenuItem::create('Translate', 'translate', ADM_TRANSLATE),
    MenuItem::create('Burime', 'burime', ADM_BURIME)
        ->sub(
            MenuItem::create('Works', 'works'),
            MenuItem::create('Authors', 'authors'),
        ),
    MenuItem::create('SuperAdmin', 'super', ADM_ADMIN),
    MenuItem::create('Commerce', 'commerce', ADM_COMMERCE)
];
