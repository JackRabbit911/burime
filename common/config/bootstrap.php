<?php

use Adm\Enum\AdminRoles;
use Sys\Response\ResponseHeader;

define('SYSPATH', DOCROOT . '../../system/');
define('FRAMEWORK', SYSPATH . 'vendor/alpha-zeta/framework/src/');
define('CONFIG', APPPATH . 'common/config/');
define('ENVPATH', DOCROOT . '../');
define('ROOTPATH', DOCROOT . '../../');
define('STORAGE', DOCROOT . '../storage/');

require_once SYSPATH . 'vendor/autoload.php';
require_once FRAMEWORK . 'autoload.php';
require_once FRAMEWORK . 'library.php';

if (is_file(FRAMEWORK . 'polyfill.php')) {
    require_once FRAMEWORK . 'polyfill.php';
}

date_default_timezone_set(env('APP_TZ'));

define('PRODUCTION', 10);
define('STAGE', 20);
define('TESTING', 30);
define('DEVELOPMENT', 40);

define('ENV', env('APP_ENV'));

define('DISPLAY_ERRORS', (ENV >= TESTING) ? true : false);

define('IS_DEBUG', false);
define('IS_CACHE', false);

define('STRICT_MODE', false);

if (PHP_SAPI === 'cli') {
    define('MODE', 'cli');
} elseif (strpos($_SERVER['REQUEST_URI'], '/api/') === 0) {
    define('MODE', 'api');
} else {
    define('MODE', 'web');
}

define('ROUTE_PATHS', [
    CONFIG . 'routes/' . MODE . '.php',
    APPPATH . 'auth/config/routes.php',
    CONFIG . 'routes/common.php',
]);

if (ENV > PRODUCTION) {
    ResponseHeader::addHeader('X-Robots-Tag', 'noindex, nofollow, noimageindex');
}

if (str_starts_with($_SERVER['REQUEST_URI'], '/api/adm')) {
    define('ADM_SEO', AdminRoles::Seo->value);
    define('ADM_BURIME', AdminRoles::Burime->value);
    define('ADM_CONTENT', AdminRoles::Content->value);
    define('ADM_USERS', AdminRoles::Users->value);
    define('ADM_DEVELOP', AdminRoles::Develop->value);
    define('ADM_DEVOPS', AdminRoles::DevOps->value);
    define('ADM_ADMIN', AdminRoles::Admin->value);
    define('ADM_COMMERCE', AdminRoles::Commerce->value);
}
